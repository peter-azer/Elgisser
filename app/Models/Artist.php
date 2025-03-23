<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    /** @use HasFactory<\Database\Factories\ArtistFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'auth_papers',
        'artist_name',
        'experience',
        'artist_bio',
        'artist_image',
    ];
}
