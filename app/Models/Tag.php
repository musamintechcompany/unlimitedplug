<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'count'];

    public static function createOrIncrement($tagName)
    {
        $tag = self::firstOrCreate(['name' => $tagName]);
        $tag->increment('count');
        return $tag;
    }

    public function decrementCount()
    {
        if ($this->count > 0) {
            $this->decrement('count');
        }
    }
}