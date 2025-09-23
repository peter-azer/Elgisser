<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Represents a payment transaction associated with an order.
 *
 * Stores gateway response details (e.g., status, amount, method) and
 * the raw payload for auditing/debugging. Soft deletes are enabled.
 */
class Payment extends BaseModel 
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory, SoftDeletes;

    // Mass assignable columns from gateway/order processing
    protected $fillable = [
        'payment_id', // Unique identifier for the payment
        'status', // Status of the payment (e.g., "paid", "pending", "failed")
        'amount', // Amount of the payment
        'currency', // Currency of the payment
        'description', // Description of the payment
        'method', // Payment method used (e.g., "credit card", "bank transfer")
        'card', // Card details (if applicable)
        'transaction_url', // URL of the transaction
        'payload', // Raw payload from the gateway
        'order_id' // Foreign key referencing the associated order
    ];

    // Cast raw payload JSON into array for convenient access
    protected $casts = [
        'payload' => 'array', // Convert payload JSON to a PHP array
    ];
    
    /**
     * The order to which this payment belongs.
     */
    public function order(){
        return $this->belongsTo(Order::class); // Define the relationship with the Order model
    }
    
}
