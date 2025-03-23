<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtWork extends Model
{
    /** @use HasFactory<\Database\Factories\ArtWorkFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'category_id',
        'title',
        'price',
        'dimensions',
        'quantity',
        'one_of_a_kind',
        'cover_image',
        'images',
        'description',
        'for_rent',
        'rent_price',
        'status'
    ];
}
