<?php

namespace App\Http\Controllers\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Interfaces\PaymentGatewayInterface;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Controller;

/**
 * Handles initiating payments and processing callbacks from the payment gateway.
 *
 * Flow overview:
 * - paymentProcess():
 *   1) Receives cart items from the request
 *   2) Creates an order via OrderController::checkout()
 *   3) Delegates to the injected PaymentGatewayInterface implementation to start the payment
 * - callBack():
 *   Handles redirect/callback from the gateway and redirects the user to the frontend with success/failure state
 */
class PaymentController extends Controller
{
    /**
     * The payment gateway implementation (bound in PaymentServiceProvider).
     */
    protected PaymentGatewayInterface $paymentGateway;
    /**
     * Temporary holder for cart items extracted from the request.
     */
    protected $cartItems;
    /**
     * The created Order instance after checkout.
     */
    protected $order;

    /**
     * Inject the payment gateway via the interface. The concrete implementation
     * is determined by the service container binding (see PaymentServiceProvider).
     */
    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Initiate the payment process.
     *
     * Steps:
     * - Extract cart items from the request
     * - Create an order using the OrderController::checkout()
     * - Delegate to the payment gateway to create a payment/invoice
     *
     * Returns a JSON response (or array) from the gateway indicating the
     * payment URL or an error message.
     */
    public function paymentProcess(Request $request)
    {
        try {
            // 1) Read items payload from the client request
            $this->cartItems = $request->input('items');
            // 2) Create an order before redirecting to payment
            $orderController = new OrderController();
            $this->order = $orderController->checkout($this->cartItems, 4);
            // dd($this->order);
            $amount = $this->order->total_amount * 100; // Convert to halalas for SAR.
            // Validate the created order and amount
            $request->merge([
                'amount' => $amount,
                'description' => 'Order Payment for #' . $this->order->order_number,
                'currency' => 'SAR', // or USD, EGP etc.
            ]);

            // 3) Ask the configured gateway to initialize the payment
            return $this->paymentGateway->sendPayment($request);
        } catch (\Exception $e) {
            // Return a standardized error response if anything fails along the way
            return response()->json(['error' => 'Payment processing failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle the payment gateway callback/redirect.
     *
     * The gateway will redirect back to the API with query params that denote
     * the transaction status. This delegates the verification/parsing to the
     * gateway service, then redirects the user to the frontend with a success flag.
     */
    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Verify and interpret the callback through the gateway implementation
        $response = $this->paymentGateway->callBack($request);

        if ($response) {
            // Successful payment -> redirect to orders page with success
            return redirect()->away('https://aljisralfanni.com/my-orders?success=true');
        }
        // Failed or canceled payment -> notify frontend via query param
        return redirect()->away('https://aljisralfanni.com/my-orders?success=false');
    }
}
