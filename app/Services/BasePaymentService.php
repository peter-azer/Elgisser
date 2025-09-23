<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Base class for payment gateway services.
 *
 * Provides a helper method to build HTTP requests with shared headers/base URL,
 * centralizing error handling and standardizing the JSON response shape.
 */
class BasePaymentService
{

    /**
     * The payment provider base URL (set by concrete implementations).
     */
    protected string $base_url;
    /**
     * Default headers to use for each request.
     */
    protected array $header;

    /**
     * Build and execute an HTTP request to the payment provider.
     *
     * @param string $method HTTP verb (e.g., 'GET', 'POST').
     * @param string $url Relative endpoint path appended to $base_url.
     * @param mixed $data Request payload (null by default).
     * @param string $type Payload type key accepted by Http::send (e.g., 'json', 'form_params').
     * @return \Illuminate\Http\JsonResponse Standardized response with success flag, status, and provider data.
     */
    protected function buildRequest($method, $url, $data = null, $type = 'json'): \Illuminate\Http\JsonResponse {

        try{
            // Delegate the request to Laravel HTTP client with shared headers and composed URL
            $response = Http::withHeaders($this->header)->send($method, $this->base_url.$url, [
                $type => $data
            ]);
            // Normalize the response to a common structure for consumers
            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
            ], $response->status());
        }catch (Exception $e){
            // Bubble up consistent error structure on exceptions
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
