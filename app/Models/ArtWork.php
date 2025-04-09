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
        'description',
        'for_rent',
        'rent_price',
        'status'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentedArtWork()
    {
        return $this->hasMany(RentedArtWork::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function artWorkImages()
    {
        return $this->hasMany(ArtWorkImages::class);
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }
    public function request(){
        return $this->hasMany(RentRequest::class);
    }
    public function history(){
        return $this->hasMany(ArtworkViewHistory::class);
    }
}
