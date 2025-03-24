<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtWorkImages extends Model
{
    protected $fillable = [
        'art_work_id',
        'image_path',
    ];

    public function artWork()
    {
        return $this->belongsTo(ArtWork::class);
    }
}
