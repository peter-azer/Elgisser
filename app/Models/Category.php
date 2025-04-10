<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'name_ar',
        'cover_image',
        'description',
        'description_ar',
        'link',
    ];

    public function artworks()
    {
        return $this->hasMany(ArtWork::class);
    }
}
