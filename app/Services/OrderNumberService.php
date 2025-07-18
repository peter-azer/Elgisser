<?php

namespace App\Services;

use App\Models\Order;

class OrderNumberService
{
    
    public static function generate()
    {
        $latest = Order::latest('id')->first();
        $number = $latest ? intval(substr($latest->order_number, 5)) + 1 : 1;
        return 'ORDR-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
