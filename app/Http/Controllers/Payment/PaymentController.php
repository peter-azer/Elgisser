<?php

namespace App\Http\Controllers\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Interfaces\PaymentGatewayInterface;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;
    protected $cartItems;
    protected $order;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function paymentProcess(Request $request)
    {
        try {

            $this->cartItems = $request->input('items');
            $orderController = new OrderController();
            $this->order = $orderController->checkout($this->cartItems, auth()->user()->id);
            return $this->paymentGateway->sendPayment($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment processing failed: ' . $e->getMessage()], 500);
        }
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        $response = $this->paymentGateway->callBack($request);

        if ($response && isset($response['order_id'])) {
            $order = \App\Models\Order::find($response['order_id']);
            if ($order) {
                $order->update(['status' => 'completed']);
            }
            return redirect()->away('https://aljisralfanni.com/my-orders?status=success');
        }

        if (isset($response['order_id'])) {
            $order = \App\Models\Order::find($response['order_id']);
            if ($order) {
                $order->update(['status' => 'canceled']);
            }
        }

        return redirect()->away('https://aljisralfanni.com/my-orders?status=failed');
    }
}
