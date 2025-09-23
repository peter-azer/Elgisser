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
        Schema::create('rented_art_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('art_work_id')->constrained()->onDelete('cascade');
            $table->foreignId('gallery_id')->constrained()->onDelete('cascade');
            $table->string('rental_code');
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->integer('rental_duration'); // in days
            $table->decimal('rental_price', 8, 2);
            $table->enum('rental_status', ['active', 'returned'])->default('active'); // e.g., active, completed, canceled
            $table->string('payment_status')->default('pending'); // e.g., pending, completed, failed
            $table->string('payment_method')->nullable(); // e.g., credit card, PayPal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rented_art_works');
    }
};
