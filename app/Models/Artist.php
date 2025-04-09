<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    /** @use HasFactory<\Database\Factories\ArtistFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'auth_papers',
        'artist_name',
        'experience',
        'artist_bio',
        'artist_image',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function artistImages(){
        return $this->hasMany(PortfolioImage::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function artWork(){
        return $this->hasMany(ArtWork::class);
    }
    public function rentedArtWork(){
        return $this->hasMany(RentedArtWork::class);
    }
    public function favorite(){
        return $this->hasMany(Favorite::class);
    }
    public function portfolioImages(){
        return $this->hasMany(PortfolioImage::class);
    }
    public function rentRequest(){
        return $this->hasMany(RentRequest::class);
    }
}
