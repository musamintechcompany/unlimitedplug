<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\OrderItem;

class DownloadController extends Controller
{
    public function download(OrderItem $orderItem)
    {
        $user = Auth::user();
        
        // Verify ownership using polymorphic relationship
        if ($orderItem->order->orderable_id !== $user->id || 
            $orderItem->order->orderable_type !== get_class($user) ||
            $orderItem->order->payment_status !== 'completed') {
            abort(403, 'Unauthorized');
        }

        // Use snapshot files from order item
        $productData = $orderItem->product_files;
        
        // Get files array
        $files = isset($productData['files']) ? $productData['files'] : [];
        
        if (empty($files)) {
            abort(404, 'No files available for download');
        }

        $filePath = $files[0]; // Download first file
        
        // Check if file exists in storage (try both locations)
        if (!Storage::exists($filePath)) {
            // Try public disk
            if (!Storage::disk('public')->exists($filePath)) {
                abort(404, 'File not found on server');
            }
            // File is on public disk
            $orderItem->incrementDownloadCount();
            if ($orderItem->product) {
                $orderItem->product->increment('downloads');
            }
            return Storage::disk('public')->download($filePath, basename($filePath));
        }

        // Increment download count
        $orderItem->incrementDownloadCount();
        
        // Increment product downloads if product still exists
        if ($orderItem->product) {
            $orderItem->product->increment('downloads');
        }

        // Return file download
        return Storage::download($filePath, basename($filePath));
    }
}
