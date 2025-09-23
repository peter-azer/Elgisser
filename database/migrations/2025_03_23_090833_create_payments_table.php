<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Reference to the associated order; cascade delete to remove payments when orders are deleted
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Gateway-provided unique identifier for the transaction
            $table->string('payment_id')->unique();
            // Current payment status from the gateway (e.g., paid, pending, failed)
            $table->string('status')->nullable();
            // Amount and currency as reported/used by the gateway
            $table->bigInteger('amount', 10)->nullable();
            $table->string('currency')->nullable();
            // Optional human-readable description
            $table->string('description')->nullable();

            // Additional gateway details
            $table->string('method')->nullable();
            $table->string('card')->nullable();
            $table->string('transaction_url')->nullable();

            // Raw gateway payload for auditing/troubleshooting
            $table->json('payload')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
