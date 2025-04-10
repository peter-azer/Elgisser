<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banners extends Model
{
    /** @use HasFactory<\Database\Factories\BannersFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'image',
        'url',
        'url_text',
    ];
}
