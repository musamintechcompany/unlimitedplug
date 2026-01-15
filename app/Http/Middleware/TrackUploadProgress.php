<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Events\FileUploadProgress;

class TrackUploadProgress
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasFile('banner') || $request->hasFile('media') || $request->hasFile('file')) {
            $uploadId = $request->header('X-Upload-ID');
            
            if ($uploadId) {
                $totalSize = 0;
                $files = array_merge(
                    $request->hasFile('banner') ? [$request->file('banner')] : [],
                    $request->hasFile('media') ? $request->file('media') : [],
                    $request->hasFile('file') ? $request->file('file') : []
                );
                
                foreach ($files as $file) {
                    $totalSize += $file->getSize();
                }
                
                session(['upload_' . $uploadId . '_total' => $totalSize]);
                session(['upload_' . $uploadId . '_loaded' => 0]);
            }
        }
        
        return $next($request);
    }
}
