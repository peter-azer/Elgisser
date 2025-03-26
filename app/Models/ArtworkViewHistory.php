<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtworkViewHistory extends Model
{
    protected $fillable = [
        'art_work_id',
        'user_id',
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function artworks(){
        return $this->belongsTo(Artwork::class);
    }
}
