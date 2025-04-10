<?php

namespace App\Services;

use App\Models\RentedArtWork;

class rentedArtworkNumberService
{
    
    public static function generate()
    {
        $latest = RentedArtWork::latest('id')->first();
        $number = $latest ? intval(substr($latest->id, 5)) + 1 : 1;
        return 'RENT-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
