<?php

namespace App\Services;

use App\Models\RentedArtWork;

class RentedArtworkNumberService
{
    
    public static function generate()
    {
        $latest = RentedArtWork::latest('id')->first();
        $number = $latest ? intval(substr($latest->rental_code, 5)) + 1 : 1;
        return 'Rent-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
