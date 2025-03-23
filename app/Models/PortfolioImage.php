<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioImage extends Model
{
    
    use SoftDeletes;

    protected $fillable = [
        'artist_id',
        'image_path',
        'image_name',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
