<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends BaseModel
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'artist_id',
        'quantity',
        'price',
        'total_price',
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function artist(){
        return $this->belongsTo(Artist::class, 'artist_id');
    }
    public function product(){
        return $this->belongsTo(ArtWork::class, 'product_id');
    }


}
