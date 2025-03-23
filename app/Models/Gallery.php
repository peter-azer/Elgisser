<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    /** @use HasFactory<\Database\Factories\GalleryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'auth_papers',
        'gallery_name',
        'gallery_description',
        'logo',
        'images',
    ];
}
