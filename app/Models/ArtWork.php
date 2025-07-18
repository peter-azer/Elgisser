<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtWork extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ArtWorkFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'category_id',
        'style_id',
        'subject_id',
        'media_id',
        'material_id',
        'title',
        'title_ar',
        'price',
        'dimensions',
        'quantity',
        'one_of_a_kind',
        'cover_image',
        'description',
        'description_ar',
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cart(){
        return $this->hasMany(Cart::class);
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
    public function style()
    {
        return $this->belongsTo(Style::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function medium()
    {
        return $this->belongsTo(Medium::class, 'media_id', 'id');
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
