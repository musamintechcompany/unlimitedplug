<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('user');
        
        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('subcategory', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply sorting
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'downloads':
                $query->orderBy('downloads', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $assets = $query->paginate(20)->withQueryString();
        return view('management.portal.admin.products.index', compact('assets'));
    }

    public function bulkAction(Request $request)
    {
        $ids = explode(',', $request->ids);
        $action = $request->action;
        
        switch ($action) {
            case 'approve':
                Product::whereIn('id', $ids)->update(['status' => 'approved', 'reviewed_at' => now()]);
                return back()->with('success', 'Products approved successfully!');
            case 'reject':
                Product::whereIn('id', $ids)->update(['status' => 'rejected', 'reviewed_at' => now()]);
                return back()->with('success', 'Products rejected successfully!');
            case 'delete':
                Product::whereIn('id', $ids)->delete();
                return back()->with('success', 'Products deleted successfully!');
        }
        
        return back();
    }
    
    public function export(Request $request)
    {
        $query = Product::with('user');
        
        if ($request->status) $query->where('status', $request->status);
        if ($request->category) $query->where('category_id', $request->category);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $products = $query->get();
        
        $csv = "ID,Name,Type,Price,Status,Downloads,Created\n";
        foreach ($products as $product) {
            $csv .= "{$product->id},\"{$product->name}\",{$product->type},{$product->price},{$product->status},{$product->downloads},{$product->created_at}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="products-' . date('Y-m-d') . '.csv"');
    }

    public function show(Product $product)
    {
        $product->load('user');
        
        // Calculate purchases and revenue
        $purchases = \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->count();
            
        $revenue = \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->sum('price');
        
        // Get download details (most recent first)
        $downloadDetails = \App\Models\OrderItem::where('product_id', $product->id)
            ->with(['order.user'])
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->where('download_count', '>', 0)
            ->orderBy('last_downloaded_at', 'desc')
            ->get();
        
        // Get purchase details (most recent first)
        $purchaseDetails = \App\Models\OrderItem::where('product_id', $product->id)
            ->with(['order.user'])
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'completed');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('management.portal.admin.products.show', compact('product', 'purchases', 'revenue', 'downloadDetails', 'purchaseDetails'));
    }

    public function create()
    {
        return view('management.portal.admin.products.create');
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
            'badge' => 'nullable|string|in:NEW,HOT,BESTSELLER,POPULAR,TRENDING,PREMIUM,EXCLUSIVE,LIMITED,FEATURED,TOP RATED,EDITOR\'S CHOICE,UPDATED,FREE',
            'license_type' => 'nullable|string|in:regular,extended,commercial',
            'banner' => 'nullable|image|max:5120',
            'media.*' => 'nullable|file|max:5120',
            'file.*' => 'nullable|file|max:20480',
            'demo_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'features.*' => 'nullable|string',
            'requirements.*' => 'nullable|string',
        ]);
        
        // Dynamically validate all currency prices (all required)
        foreach (array_keys(config('payment.currencies')) as $currencyCode) {
            $lowerCode = strtolower($currencyCode);
            $request->validate([
                "{$lowerCode}_price" => 'required|numeric|min:0',
                "{$lowerCode}_list_price" => 'nullable|numeric|min:0',
            ]);
        }

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

        // Process features array - filter empty and convert to array or null
        $features = null;
        if ($request->has('features')) {
            $filtered = array_values(array_filter($request->input('features'), fn($f) => !empty(trim($f))));
            $features = !empty($filtered) ? $filtered : null;
        }

        // Process requirements array - filter empty and convert to array or null
        $requirements = null;
        if ($request->has('requirements')) {
            $filtered = array_values(array_filter($request->input('requirements'), fn($r) => !empty(trim($r))));
            $requirements = !empty($filtered) ? $filtered : null;
        }

        // Get subcategory name for storage
        $subcategory = $validated['subcategory_id'] ? \App\Models\Subcategory::find($validated['subcategory_id']) : null;
        
        $asset = Product::create([
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
            'features' => $features,
            'requirements' => $requirements,
            'badge' => $validated['badge'],
            'license_type' => $request->input('license_type'),
            'status' => $request->input('status', 'draft'),
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'admin_id' => auth()->guard('admin')->id(), // Track which admin created it
            'reviewed_at' => in_array($request->input('status'), ['approved', 'rejected']) ? now() : null,
        ]);
        
        // Save USD pricing
        $asset->prices()->create([
            'currency_code' => 'USD',
            'price' => $validated['usd_price'],
            'list_price' => $validated['usd_list_price'],
        ]);
        
        // Save all other currency pricing
        foreach (array_keys(config('payment.currencies')) as $currencyCode) {
            if ($currencyCode === 'USD') continue; // Already saved
            
            $lowerCode = strtolower($currencyCode);
            $price = $request->input("{$lowerCode}_price");
            $listPrice = $request->input("{$lowerCode}_list_price");
            
            if ($price || $listPrice) {
                $asset->prices()->create([
                    'currency_code' => $currencyCode,
                    'price' => $price,
                    'list_price' => $listPrice,
                ]);
            }
        }

        if ($request->input('action') === 'create_another') {
            return redirect()->route('admin.products.create')->with('success', 'Product created successfully!');
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('management.portal.admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Check if this is a status update (from show page) or full edit
        if ($request->has('status') && !$request->has('name')) {
            $validated = $request->validate([
                'status' => 'required|in:draft,pending,approved,rejected',
                'is_featured' => 'boolean',
            ]);
            $product->update($validated);
            return redirect()->route('admin.products.index')
                            ->with('success', 'Product status updated successfully!');
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
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|in:NEW,HOT,BESTSELLER,POPULAR,TRENDING,PREMIUM,EXCLUSIVE,LIMITED,FEATURED,TOP RATED,EDITOR\'S CHOICE,UPDATED,FREE',
            'license_type' => 'nullable|string|in:regular,extended,commercial',
            'banner' => 'nullable|image|max:5120',
            'media.*' => 'nullable|file|max:5120',
            'file.*' => 'nullable|file|max:20480',
            'demo_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'features.*' => 'nullable|string',
            'requirements.*' => 'nullable|string',
        ]);
        
        // Dynamically validate all currency prices (all required)
        foreach (array_keys(config('payment.currencies')) as $currencyCode) {
            $lowerCode = strtolower($currencyCode);
            $request->validate([
                "{$lowerCode}_price" => 'required|numeric|min:0',
                "{$lowerCode}_list_price" => 'nullable|numeric|min:0',
            ]);
        }

        // Handle banner upload
        $bannerPath = $product->banner;
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($bannerPath) {
                Storage::disk('public')->delete($bannerPath);
            }
            $bannerPath = $request->file('banner')->store('assets/banners', 'public');
        }

        // Handle new media files
        $currentMedia = $product->media ?? [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $currentMedia[] = $file->store('assets/media', 'public');
            }
        }

        // Handle new product files
        $currentFiles = $product->file ?? [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $currentFiles[] = $file->store('assets/files', 'public');
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

        // Process features array - filter empty and convert to array or null
        $features = null;
        if ($request->has('features')) {
            $filtered = array_values(array_filter($request->input('features'), fn($f) => !empty(trim($f))));
            $features = !empty($filtered) ? $filtered : null;
        }

        // Process requirements array - filter empty and convert to array or null
        $requirements = null;
        if ($request->has('requirements')) {
            $filtered = array_values(array_filter($request->input('requirements'), fn($r) => !empty(trim($r))));
            $requirements = !empty($filtered) ? $filtered : null;
        }

        // Get subcategory name for storage
        $subcategory = $validated['subcategory_id'] ? \App\Models\Subcategory::find($validated['subcategory_id']) : null;
        
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'subcategory' => $subcategory ? $subcategory->name : null,
            'price' => $validated['usd_price'],
            'list_price' => $validated['usd_list_price'],
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'badge' => $validated['badge'],
            'license_type' => $request->input('license_type'),
            'banner' => $bannerPath,
            'media' => $currentMedia,
            'file' => $currentFiles,
            'demo_url' => $validated['demo_url'],
            'tags' => $tags,
            'features' => $features,
            'requirements' => $requirements,
        ]);
        
        // Update USD pricing
        $product->prices()->updateOrCreate(
            ['currency_code' => 'USD'],
            [
                'price' => $validated['usd_price'],
                'list_price' => $validated['usd_list_price'],
            ]
        );
        
        // Update all other currency pricing
        foreach (array_keys(config('payment.currencies')) as $currencyCode) {
            if ($currencyCode === 'USD') continue; // Already updated
            
            $lowerCode = strtolower($currencyCode);
            $price = $request->input("{$lowerCode}_price");
            $listPrice = $request->input("{$lowerCode}_list_price");
            
            if ($price || $listPrice) {
                $product->prices()->updateOrCreate(
                    ['currency_code' => $currencyCode],
                    [
                        'price' => $price,
                        'list_price' => $listPrice,
                    ]
                );
            } else {
                // Remove pricing if both fields are empty
                $product->prices()->where('currency_code', $currencyCode)->delete();
            }
        }

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete banner if exists
        if ($product->banner) {
            Storage::disk('public')->delete($product->banner);
        }
        
        // Delete media files if exist
        if ($product->media) {
            foreach ($product->media as $media) {
                Storage::disk('public')->delete($media);
            }
        }
        
        // Delete asset files if exist
        if ($product->file) {
            foreach ($product->file as $file) {
                Storage::disk('public')->delete($file);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')
                        ->with('success', 'Product deleted successfully!');
    }

    public function deleteBanner(Product $product)
    {
        if ($product->banner) {
            Storage::disk('public')->delete($product->banner);
            $product->update(['banner' => null]);
        }
        return response()->json(['success' => true]);
    }

    public function deleteMedia(Product $product, $index)
    {
        $media = $product->media;
        if (isset($media[$index])) {
            Storage::disk('public')->delete($media[$index]);
            unset($media[$index]);
            $product->update(['media' => array_values($media)]);
        }
        return response()->json(['success' => true]);
    }

    public function deleteFile(Product $product, $index)
    {
        $files = $product->file;
        if (isset($files[$index])) {
            Storage::disk('public')->delete($files[$index]);
            unset($files[$index]);
            $product->update(['file' => array_values($files)]);
        }
        return response()->json(['success' => true]);
    }
}