<?php

namespace App\Providers;

use App\Interfaces\PaymentGatewayInterface;
use App\Services\MoyasarPaymentService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the PaymentGatewayInterface to a concrete implementation.
        // This allows the application to depend on the interface while
        // swapping out gateway providers (e.g., Moyasar, Stripe) centrally.
        $this->app->bind(PaymentGatewayInterface::class, MoyasarPaymentService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Nothing to bootstrap for payments at the moment.
    }
}
