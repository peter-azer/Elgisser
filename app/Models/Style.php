<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
    ];

    /**
     * Get the artworks associated with the style.
     */
    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }
}
