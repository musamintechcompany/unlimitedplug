<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadProgress implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $uploadId;
    public $progress;
    public $fileName;

    public function __construct($uploadId, $progress, $fileName = null)
    {
        $this->uploadId = $uploadId;
        $this->progress = $progress;
        $this->fileName = $fileName;
    }

    public function broadcastOn()
    {
        return new Channel('upload.' . $this->uploadId);
    }

    public function broadcastAs()
    {
        return 'upload.progress';
    }
}
