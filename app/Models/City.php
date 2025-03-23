<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'city_name',
        'city_code',
        'country_id',
        'area_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function area()
    {
        return $this->hasMany(Area::class);
    }
}
