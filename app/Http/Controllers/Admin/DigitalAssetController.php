<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalAsset;
use Illuminate\Http\Request;

class DigitalAssetController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalAsset::with('user')->latest();
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $assets = $query->paginate(20);
        return view('management.portal.admin.digital-assets.index', compact('assets'));
    }

    public function show(DigitalAsset $digitalAsset)
    {
        $digitalAsset->load('user');
        
        // Calculate purchases and revenue
        $purchases = \App\Models\OrderItem::where('digital_asset_id', $digitalAsset->id)
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->count();
            
        $revenue = \App\Models\OrderItem::where('digital_asset_id', $digitalAsset->id)
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->sum('price');
        
        // Get download details (most recent first)
        $downloadDetails = \App\Models\OrderItem::where('digital_asset_id', $digitalAsset->id)
            ->with(['order.user'])
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->where('download_count', '>', 0)
            ->orderBy('last_downloaded_at', 'desc')
            ->get();
        
        // Get purchase details (most recent first)
        $purchaseDetails = \App\Models\OrderItem::where('digital_asset_id', $digitalAsset->id)
            ->with(['order.user'])
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('management.portal.admin.digital-assets.show', compact('digitalAsset', 'purchases', 'revenue', 'downloadDetails', 'purchaseDetails'));
    }

    public function create()
    {
        return view('management.portal.admin.digital-assets.create');
    }

    public function store(Request $request)
    {
        // Increase execution time and memory for file uploads
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '256M');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:website,template,plugin,service,digital',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'usd_price' => 'required|numeric|min:0',
            'usd_list_price' => 'nullable|numeric|min:0',
            'ngn_price' => 'nullable|numeric|min:0',
            'ngn_list_price' => 'nullable|numeric|min:0',
            'badge' => 'nullable|string|in:NEW,HOT,BESTSELLER,POPULAR,TRENDING,PREMIUM,EXCLUSIVE,LIMITED,FEATURED,TOP RATED,EDITOR\'S CHOICE,UPDATED,FREE',
            'banner' => 'nullable|image|max:5120',
            'media.*' => 'nullable|file|max:5120', // 5MB per media file
            'file.*' => 'nullable|file|max:20480', // 20MB per asset file
            'demo_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'features' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        // Handle banner image
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('assets/banners', 'public');
        }

        // Handle media files
        $mediaFiles = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaFiles[] = $file->store('assets/media', 'public');
            }
        }

        // Handle digital asset files
        $assetFiles = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $assetFiles[] = $file->store('assets/files', 'public');
            }
        }

        // Process tags (convert to hashtag format if not already)
        $tags = null;
        if ($validated['tags']) {
            $tagArray = array_map('trim', explode(',', $validated['tags']));
            $tags = array_map(function($tag) {
                return strpos($tag, '#') === 0 ? $tag : '#' . $tag;
            }, $tagArray);
        }

        // Get subcategory name for storage
        $subcategory = $validated['subcategory_id'] ? \App\Models\Subcategory::find($validated['subcategory_id']) : null;
        
        $asset = DigitalAsset::create([
            'user_id' => null, // Admin-created assets don't need user_id
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'subcategory' => $subcategory ? $subcategory->name : null, // Store subcategory name for compatibility
            'price' => $validated['usd_price'], // Default price in USD
            'list_price' => $validated['usd_list_price'],
            'banner' => $bannerPath,
            'media' => $mediaFiles,
            'file' => $assetFiles,
            'demo_url' => $validated['demo_url'],
            'tags' => $tags,
            'features' => $validated['features'] ? explode("\n", $validated['features']) : null,
            'requirements' => $validated['requirements'],
            'badge' => $validated['badge'],
            'status' => 'approved', // Admin-created assets are auto-approved
            'admin_id' => auth()->guard('admin')->id(), // Track which admin created it
            'reviewed_at' => now(), // Mark as reviewed
        ]);
        
        // Save USD pricing
        $asset->prices()->create([
            'currency_code' => 'USD',
            'price' => $validated['usd_price'],
            'list_price' => $validated['usd_list_price'],
        ]);
        
        // Save NGN pricing if provided
        if ($validated['ngn_price'] || $validated['ngn_list_price']) {
            $asset->prices()->create([
                'currency_code' => 'NGN',
                'price' => $validated['ngn_price'],
                'list_price' => $validated['ngn_list_price'],
            ]);
        }

        return redirect()->route('admin.digital-assets.index')->with('success', 'Digital asset created successfully!');
    }

    public function edit(DigitalAsset $digitalAsset)
    {
        return view('management.portal.admin.digital-assets.edit', compact('digitalAsset'));
    }

    public function update(Request $request, DigitalAsset $digitalAsset)
    {
        // Check if this is a status update (from show page) or full edit
        if ($request->has('status') && !$request->has('name')) {
            $validated = $request->validate([
                'status' => 'required|in:draft,pending,approved,rejected',
                'is_featured' => 'boolean',
            ]);
            $digitalAsset->update($validated);
            return redirect()->route('admin.digital-assets.index')
                            ->with('success', 'Asset status updated successfully!');
        }

        // Full edit update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:website,template,plugin,service,digital',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'usd_price' => 'required|numeric|min:0',
            'usd_list_price' => 'nullable|numeric|min:0',
            'ngn_price' => 'nullable|numeric|min:0',
            'ngn_list_price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|in:NEW,HOT,BESTSELLER,POPULAR,TRENDING,PREMIUM,EXCLUSIVE,LIMITED,FEATURED,TOP RATED,EDITOR\'S CHOICE,UPDATED,FREE',
            'banner' => 'nullable|image|max:5120',
            'media.*' => 'nullable|file|max:5120',
            'demo_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'features' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        // Handle banner upload
        $bannerPath = $digitalAsset->banner;
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($bannerPath) {
                Storage::disk('public')->delete($bannerPath);
            }
            $bannerPath = $request->file('banner')->store('assets/banners', 'public');
        }

        // Handle new media files
        $currentMedia = $digitalAsset->media ?? [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $currentMedia[] = $file->store('assets/media', 'public');
            }
        }

        // Process tags
        $tags = null;
        if ($validated['tags']) {
            $tagArray = array_map('trim', explode(',', $validated['tags']));
            $tags = array_map(function($tag) {
                return strpos($tag, '#') === 0 ? $tag : '#' . $tag;
            }, $tagArray);
        }

        // Get subcategory name for storage
        $subcategory = $validated['subcategory_id'] ? \App\Models\Subcategory::find($validated['subcategory_id']) : null;
        
        $digitalAsset->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'subcategory' => $subcategory ? $subcategory->name : null, // Store subcategory name for compatibility
            'price' => $validated['usd_price'], // Default price in USD
            'list_price' => $validated['usd_list_price'],
            'is_featured' => $request->has('is_featured'),
            'badge' => $validated['badge'],
            'banner' => $bannerPath,
            'media' => $currentMedia,
            'demo_url' => $validated['demo_url'],
            'tags' => $tags,
            'features' => $validated['features'] ? explode("\n", $validated['features']) : null,
            'requirements' => $validated['requirements'],
        ]);
        
        // Update USD pricing
        $digitalAsset->prices()->updateOrCreate(
            ['currency_code' => 'USD'],
            [
                'price' => $validated['usd_price'],
                'list_price' => $validated['usd_list_price'],
            ]
        );
        
        // Update NGN pricing
        if ($validated['ngn_price'] || $validated['ngn_list_price']) {
            $digitalAsset->prices()->updateOrCreate(
                ['currency_code' => 'NGN'],
                [
                    'price' => $validated['ngn_price'],
                    'list_price' => $validated['ngn_list_price'],
                ]
            );
        } else {
            // Remove NGN pricing if both fields are empty
            $digitalAsset->prices()->where('currency_code', 'NGN')->delete();
        }

        return redirect()->route('admin.digital-assets.index')
                        ->with('success', 'Digital asset updated successfully!');
    }

    public function destroy(DigitalAsset $digitalAsset)
    {
        // Delete banner if exists
        if ($digitalAsset->banner) {
            Storage::disk('public')->delete($digitalAsset->banner);
        }
        
        // Delete media files if exist
        if ($digitalAsset->media) {
            foreach ($digitalAsset->media as $media) {
                Storage::disk('public')->delete($media);
            }
        }
        
        // Delete asset files if exist
        if ($digitalAsset->file) {
            foreach ($digitalAsset->file as $file) {
                Storage::disk('public')->delete($file);
            }
        }
        
        $digitalAsset->delete();
        return redirect()->route('admin.digital-assets.index')
                        ->with('success', 'Asset deleted successfully!');
    }

    public function deleteBanner(DigitalAsset $digitalAsset)
    {
        if ($digitalAsset->banner) {
            Storage::disk('public')->delete($digitalAsset->banner);
            $digitalAsset->update(['banner' => null]);
        }
        return response()->json(['success' => true]);
    }

    public function deleteMedia(DigitalAsset $digitalAsset, $index)
    {
        $media = $digitalAsset->media;
        if (isset($media[$index])) {
            Storage::disk('public')->delete($media[$index]);
            unset($media[$index]);
            $digitalAsset->update(['media' => array_values($media)]);
        }
        return response()->json(['success' => true]);
    }

    public function deleteFile(DigitalAsset $digitalAsset, $index)
    {
        $files = $digitalAsset->file;
        if (isset($files[$index])) {
            Storage::disk('public')->delete($files[$index]);
            unset($files[$index]);
            $digitalAsset->update(['file' => array_values($files)]);
        }
        return response()->json(['success' => true]);
    }
}