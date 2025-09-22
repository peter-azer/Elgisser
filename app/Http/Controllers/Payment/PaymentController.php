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
        try{
            
            return $this->paymentGateway->sendPayment($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment processing failed: ' . $e->getMessage()], 500);
        }
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        $response = $this->paymentGateway->callBack($request);

        if ($response) {
            return redirect()->away('http://localhost:5173/cart?status=success')->with(['request' => $request->all()]);
        }
        return redirect()->away('http://localhost:5173/payment/failure?status=failed');
    }

    
}
