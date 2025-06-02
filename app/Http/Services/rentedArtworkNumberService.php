<?php

namespace App\Services;

use App\Models\RentedArtWork;

class RentedArtworkNumberService
{
    
    public static function generate()
    {
        $latest = RentedArtWork::latest('id')->first();
        if ($latest && preg_match('/RENT-(\d+)/', $latest->number, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }
        return 'RENT-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
