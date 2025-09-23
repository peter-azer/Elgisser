<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MoyasarPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    protected $api_secret;
    public function __construct()
    {
        $this->base_url = env('MOYASAR_BASE_URL');
        $this->api_secret = env('MOYASAR_SECRET_KEY');
        $this->header = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Basic ".base64_encode("$this->api_secret:''"),
        ];
    }

    public function sendPayment(Request $request)
    {
        //validate data
        $data = $request->all();
        // Build success URL and include order_id if available so callback can update order status
        $baseSuccessUrl = $request->getSchemeAndHttpHost() . '/api/payment/callback';
        $orderId = $request->input('order_id');
        $data['success_url'] = $orderId
            ? $baseSuccessUrl . '?order_id=' . urlencode($orderId)
            : $baseSuccessUrl;
        $response = $this->buildRequest('POST', '/v1/invoices', $data);
        //handle payment response
        if($response->getData(true)['success']){
            return['success'=>true, 'url'=>$response->getData(true)['data']['url']];
        }
        return['success'=>false, 'url'=>$response];
    }

    public function callBack(Request $request)
    {
        $response_status = $request->get('status');
        Storage::put('moyasar_response.json', json_encode($request->all()));
        if(isset($response_status) && $response_status === 'paid') {
            return true;
        }
        return false;
    }

}