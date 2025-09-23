<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

/**
 * Contract for payment gateway integrations.
 *
 * Implementations should encapsulate the logic for initiating a payment
 * (e.g., creating an invoice or checkout session) and handling the
 * gateway callback/redirect to confirm the transaction status.
 */
interface PaymentGatewayInterface
{
    /**
     * Initialize a payment with the gateway (e.g., create invoice/checkout).
     * Should return data needed by the client to proceed (commonly a URL).
     */
    public function sendPayment(Request $request);
    /**
     * Handle the gateway callback/redirect and determine success/failure.
     * Should return a boolean or a structured response indicating status.
     */
    public function callBack(Request $request);
}
