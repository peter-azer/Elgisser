<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentedArtWork extends BaseModel 
{
    /** @use HasFactory<\Database\Factories\RentedArtWorkFactory> */
    use HasFactory;

    protected $fillable = [
        'art_work_id',
        'gallery_id',
        'rental_code',
        'rental_start_date',
        'rental_end_date',
        'rental_duration',
        'rental_price',
        'rental_status',
        'payment_status',
        'payment_method',
    ];
    public function artWork()
    {
        return $this->belongsToMany(ArtWork::class);
    }
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function gallery()
    {
        return $this->belongsToMany(Gallery::class);
    }
}
