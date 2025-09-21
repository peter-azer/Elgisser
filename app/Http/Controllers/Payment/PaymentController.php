<?php

namespace App\Http\Controllers\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Interfaces\PaymentGatewayInterface;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function paymentProcess(Request $request)
    {
        return $this->paymentGateway->sendPayment($request);
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        $response = $this->paymentGateway->callBack($request);

        if ($response) {
            return redirect()->away(env('FRONTEND_URL') . '/payment/success');
        }
        return redirect()->away(env('FRONTEND_URL') . '/payment/failure');
    }

    
}
