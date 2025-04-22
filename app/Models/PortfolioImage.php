<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioImage extends BaseModel 
{
    
    use SoftDeletes;

    protected $fillable = [
        'artist_id',
        'image_path',
        'image_name',
        'image_name_ar',
        'image_type',
        'image_type_ar',
        'image_size',
        'image_description',
        'image_description_ar',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
