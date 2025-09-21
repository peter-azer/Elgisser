<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends BaseModel 
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'status',
        'amount',
        'currency',
        'description',
        'method',
        'card',
        'transaction_url',
        'payload',
        'order_id'
    ];


    protected $casts = [
        'payload' => 'array',
    ];
    
    public function order(){
        return $this->belongsTo(Order::class);
    }
    
}
