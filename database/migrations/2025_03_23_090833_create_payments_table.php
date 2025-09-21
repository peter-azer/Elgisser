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
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->string('payment_id')->unique();
            $table->string('status')->nullable();
            $table->bigInteger('amount', 10)->nullable();
            $table->string('currency')->nullable();
            $table->string('description')->nullable();

            $table->string('method')->nullable();
            $table->string('card')->nullable();
            $table->string('transaction_url')->nullable();

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
