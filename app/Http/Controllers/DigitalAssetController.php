<?php

namespace App\Http\Controllers;

use App\Models\DigitalAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalAssetController extends Controller
{
    public function index()
    {
        $assets = auth()->user()->digitalAssets()->latest()->paginate(12);
        return view('digital-assets.index', compact('assets'));
    }

    public function create()
    {
        return view('digital-assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:website,template,plugin,service,digital',
            'subcategory' => 'required|string',
            'usd_price' => 'required|numeric|min:0',
            'usd_list_price' => 'nullable|numeric|min:0',
            'ngn_price' => 'nullable|numeric|min:0',
            'ngn_list_price' => 'nullable|numeric|min:0',
            'preview_image' => 'required|image|max:2048',
            'gallery_images.*' => 'image|max:2048',
            'download_file' => 'nullable|file|max:51200',
            'demo_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'features' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        // Handle preview image
        $previewPath = $request->file('preview_image')->store('assets/previews', 'public');

        // Handle gallery images
        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('assets/gallery', 'public');
            }
        }

        // Handle download file
        $downloadPath = null;
        if ($request->hasFile('download_file')) {
            $downloadPath = $request->file('download_file')->store('assets/downloads', 'private');
        }

        $asset = DigitalAsset::create([
            'user_id' => auth()->id(),
            'name' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'subcategory' => $validated['subcategory'],
            'price' => $validated['usd_price'], // Default price in USD
            'list_price' => $validated['usd_list_price'],
            'banner' => $previewPath,
            'media' => $galleryPaths,
            'file' => $downloadPath ? [$downloadPath] : null,
            'demo_url' => $validated['demo_url'],
            'tags' => $validated['tags'] ? explode(',', $validated['tags']) : null,
            'features' => $validated['features'] ? explode("\n", $validated['features']) : null,
            'requirements' => $validated['requirements'],
            'status' => 'pending',
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

        return redirect()->route('digital-assets.index')->with('success', 'Digital asset created successfully!');
    }

    public function show(DigitalAsset $digitalAsset)
    {
        return view('digital-assets.show', compact('digitalAsset'));
    }

    public function edit(DigitalAsset $digitalAsset)
    {
        $this->authorize('update', $digitalAsset);
        
        // Load existing pricing data
        $usdPricing = $digitalAsset->prices()->where('currency_code', 'USD')->first();
        $ngnPricing = $digitalAsset->prices()->where('currency_code', 'NGN')->first();
        
        return view('digital-assets.edit', compact('digitalAsset', 'usdPricing', 'ngnPricing'));
    }

    public function update(Request $request, DigitalAsset $digitalAsset)
    {
        $this->authorize('update', $digitalAsset);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:website,template,plugin,service,digital',
            'subcategory' => 'required|string',
            'usd_price' => 'required|numeric|min:0',
            'usd_list_price' => 'nullable|numeric|min:0',
            'ngn_price' => 'nullable|numeric|min:0',
            'ngn_list_price' => 'nullable|numeric|min:0',
            'demo_url' => 'nullable|url',
            'tags' => 'nullable|string',
            'features' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $digitalAsset->update([
            'name' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'subcategory' => $validated['subcategory'],
            'price' => $validated['usd_price'],
            'list_price' => $validated['usd_list_price'],
            'demo_url' => $validated['demo_url'],
            'tags' => $validated['tags'] ? explode(',', $validated['tags']) : null,
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

        return redirect()->route('digital-assets.index')->with('success', 'Digital asset updated successfully!');
    }

    public function destroy(DigitalAsset $digitalAsset)
    {
        $this->authorize('delete', $digitalAsset);
        
        // Delete files
        if ($digitalAsset->preview_image) {
            Storage::disk('public')->delete($digitalAsset->preview_image);
        }
        if ($digitalAsset->gallery_images) {
            foreach ($digitalAsset->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        if ($digitalAsset->download_file) {
            Storage::disk('private')->delete($digitalAsset->download_file);
        }

        $digitalAsset->delete();

        return redirect()->route('digital-assets.index')->with('success', 'Digital asset deleted successfully!');
    }
}