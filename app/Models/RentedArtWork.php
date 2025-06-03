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
        'start_date',
        'end_date',
        'price',
        'status',
    ];
    public function artWork()
    {
        return $this->belongsTo(ArtWork::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
