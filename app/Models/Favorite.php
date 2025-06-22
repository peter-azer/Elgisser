<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends BaseModel
{
    /** @use HasFactory<\Database\Factories\FavoriteFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'art_work_id',
        'artist_id',
        'type', // 'art_work' or 'artist'
    ];
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function artWork()
    {
        return $this->hasMany(ArtWork::class);
    }
    public function artist()
    {
        return $this->hasMany(Artist::class);
    }
    public function isArtWork()
    {
        return $this->type === 'art_work';
    }
    public function isArtist()
    {
        return $this->type === 'artist';
    }
}
