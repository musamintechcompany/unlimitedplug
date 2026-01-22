<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $sitemap .= $this->addUrl(url('/'), '1.0', 'daily', now());
        
        // Static pages
        $sitemap .= $this->addUrl(url('/marketplace'), '0.9', 'daily', now());
        $sitemap .= $this->addUrl(url('/how-it-works'), '0.5', 'monthly', now());
        
        // Categories
        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $sitemap .= $this->addUrl(
                url('/c/' . $category->slug),
                '0.8',
                'weekly',
                $category->updated_at
            );
        }
        
        // Products
        $products = Product::where('status', 'approved')
            ->where('is_active', true)
            ->get();
        foreach ($products as $product) {
            $sitemap .= $this->addUrl(
                url('/marketplace/product/' . $product->id),
                '0.7',
                'weekly',
                $product->updated_at
            );
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
    
    private function addUrl($loc, $priority, $changefreq, $lastmod)
    {
        return '<url>' .
            '<loc>' . htmlspecialchars($loc) . '</loc>' .
            '<lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>' .
            '<changefreq>' . $changefreq . '</changefreq>' .
            '<priority>' . $priority . '</priority>' .
            '</url>';
    }
}
