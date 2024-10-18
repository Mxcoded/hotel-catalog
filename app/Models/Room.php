<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

   // App\Models\Room.php


    protected $fillable = ['name', 'description', 'price', 'features', 'available', 'available_from', 'image_path'];

    // Add a computed property for actual availability
    public function isAvailable(): bool
    {
        if ($this->available) {
            if ($this->available_from) {
                return now()->isAfter($this->available_from);
            }
            return true;
        }
        return false;
    }


    protected $casts = [
        'features' => 'array', // Cast features to array
    ];

    // Define relationship if you are using a separate table for images
    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }
}

