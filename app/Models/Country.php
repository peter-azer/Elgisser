<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'country_name',
        'country_code',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
