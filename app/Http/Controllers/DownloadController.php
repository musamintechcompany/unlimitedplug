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
        
        // Handle both old format (array) and new format (object)
        $files = is_array($productData) && isset($productData['files']) 
            ? $productData['files'] 
            : $productData;
        
        if (empty($files)) {
            abort(404, 'No files available for download');
        }

        $filePath = $files[0]; // Download first file
        
        // Check if file exists in storage
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found on server');
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
