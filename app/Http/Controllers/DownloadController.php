<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\OrderItem;
use App\Models\DigitalAsset;

class DownloadController extends Controller
{
    public function download(OrderItem $orderItem)
    {
        $user = Auth::user();
        
        // Verify ownership
        if ($orderItem->order->user_id !== $user->id || $orderItem->order->payment_status !== 'completed') {
            abort(403, 'Unauthorized');
        }

        $asset = $orderItem->digitalAsset;
        
        if (!$asset || !$asset->file) {
            abort(404, 'Digital asset or file not found');
        }

        // Get the first file from the JSON array
        $files = is_array($asset->file) ? $asset->file : json_decode($asset->file, true);
        
        if (empty($files)) {
            abort(404, 'No files available for download');
        }

        $filePath = $files[0]; // Download first file
        
        // Check if file exists in storage (files are stored privately)
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found on server');
        }

        // Increment download count
        $orderItem->incrementDownloadCount();
        $asset->increment('downloads');

        // Return file download
        return Storage::download($filePath, basename($filePath));
    }
}
