<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentRequest extends Model
{
    /** @use HasFactory<\Database\Factories\RentRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'art_work_id',
        'artist_id',
        'rental_start_date',
        'rental_end_date',
        'rental_duration',
        'status',
    ];

    public function gallery(){
        return $this->belongsTo(Gallery::class);
    }

    public function artwork(){
        return $this->belongsTo(ArtWork::class);
    }
    public function artist(){
        return $this->belongsTo(Artist::class);
    }
}
