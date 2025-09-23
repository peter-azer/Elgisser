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
            $id = $this->order[0]->id;
            $request->merge(['order_id' => $id]);
            return $this->paymentGateway->sendPayment($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment processing failed: ' . $e->getMessage()], 500);
        }
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {

        $response = $this->paymentGateway->callBack($request);
        // $order = \App\Models\Order::findOrFail($request->input('order_id'));

        dd($request);

        if ($response) {
            // $order->update(['status' => 'completed']);
            return redirect()->away('https://aljisralfanni.com/my-orders?success=true');
        }
        // $order->update(['status' => 'canceled']);
        return redirect()->away('https://aljisralfanni.com/my-orders?success=false');
    }
}
