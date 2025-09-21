<?php

namespace App\Http\Controllers\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\MoyasarPaymentService;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected MoyasarPaymentService $paymentGateway;

    public function __construct(MoyasarPaymentService $paymentGateway)
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
            return redirect()->away(env('FRONTEND_URL') . '/payment/success');
        }
        return redirect()->away(env('FRONTEND_URL') . '/payment/failure');
    }

    
}
