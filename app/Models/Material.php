<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
    ];

    /**
     * Get the artworks associated with the material.
     */
    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }
}
