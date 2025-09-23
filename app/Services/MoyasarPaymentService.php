<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Moyasar payment gateway implementation.
 *
 * Reads configuration from environment variables, prepares authenticated
 * requests, creates invoices, and validates callback responses.
 */
class MoyasarPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Secret key used for Basic authentication with Moyasar API.
     */
    protected $api_secret;

    /**
     * Configure base URL, credentials and default headers.
     */
    public function __construct()
    {
        $this->base_url = env('MOYASAR_BASE_URL');
        $this->api_secret = env('MOYASAR_SECRET_KEY');
        // Default headers for Moyasar API, including Basic Auth with the secret key
        $this->header = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Basic ".base64_encode("$this->api_secret:''"),
        ];
    }

    /**
     * Create a Moyasar invoice and return the hosted payment page URL.
     *
     * Expects all required fields in the incoming request per Moyasar API,
     * and injects a success_url pointing to the API callback route.
     */
    public function sendPayment(Request $request)
    {
        // Collect the required request payload from client
        $data = $request->all();
        // Provide callback URL for successful payment (handled by PaymentController::callBack)
        $data['success_url'] = $request->getSchemeAndHttpHost(). '/api/payment/callback';
        // Create invoice via Moyasar API
        $response = $this->buildRequest('POST', '/v1/invoices', $data);
        // Parse and normalize the response for the caller (PaymentController)
        if($response->getData(true)['success']){
            return['success'=>true, 'url'=>$response->getData(true)['data']['url']];
        }
        return['success'=>false, 'url'=>$response];
    }

    /**
     * Handle the redirect/callback from Moyasar.
     *
     * Stores the raw callback payload for auditing, then reads the 'status'
     * parameter and returns true only if it equals 'paid'.
     */
    public function callBack(Request $request)
    {
        $response_status = $request->get('status');
        // Persist callback content for troubleshooting/record keeping
        Storage::put('moyasar_response.json', json_encode($request->all()));
        // Consider transaction successful only when status is 'paid'
        if(isset($response_status) && $response_status === 'paid') {
            return true;
        }
        return false;
    }
}