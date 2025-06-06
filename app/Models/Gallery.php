<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends BaseModel 
{
    /** @use HasFactory<\Database\Factories\GalleryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'auth_papers',
        'gallery_name',
        'gallery_name_ar',
        'gallery_description',
        'gallery_description_ar',
        'logo',
        'images',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function request(){
        return $this->hasMany(RentRequest::class);
    }
}
