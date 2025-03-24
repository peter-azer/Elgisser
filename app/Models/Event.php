<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gallery_id',
        'event_name',
        'event_start_date',
        'event_end_date',
        'event_duration',
        'event_location',
        'event_link',
        'event_description',
        'event_image',
        'event_status',
        'is_approved'
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
