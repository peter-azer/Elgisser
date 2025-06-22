<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends BaseModel
{
    /** @use HasFactory<\Database\Factories\FavoriteFactory> */
    use HasFactory;

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
        return $this->belongsTo(User::class);
    }
    public function artWork()
    {
        return $this->belongsTo(ArtWork::class);
    }
    public function artist()
    {
        return $this->belongsTo(Artist::class);
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
