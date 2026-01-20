<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $assets = auth()->user()->products()->latest()->paginate(12);
        return view('products.index', compact('assets'));
    }

    public function create()
    {
        return view('products.create');
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

        $asset = Product::create([
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

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        // Load existing pricing data
        $usdPricing = $product->prices()->where('currency_code', 'USD')->first();
        $ngnPricing = $product->prices()->where('currency_code', 'NGN')->first();
        
        return view('products.edit', compact('product', 'usdPricing', 'ngnPricing'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
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

        $product->update([
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
        $product->prices()->updateOrCreate(
            ['currency_code' => 'USD'],
            [
                'price' => $validated['usd_price'],
                'list_price' => $validated['usd_list_price'],
            ]
        );
        
        // Update NGN pricing
        if ($validated['ngn_price'] || $validated['ngn_list_price']) {
            $product->prices()->updateOrCreate(
                ['currency_code' => 'NGN'],
                [
                    'price' => $validated['ngn_price'],
                    'list_price' => $validated['ngn_list_price'],
                ]
            );
        } else {
            // Remove NGN pricing if both fields are empty
            $product->prices()->where('currency_code', 'NGN')->delete();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        // Delete files
        if ($product->preview_image) {
            Storage::disk('public')->delete($product->preview_image);
        }
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        if ($product->download_file) {
            Storage::disk('private')->delete($product->download_file);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}