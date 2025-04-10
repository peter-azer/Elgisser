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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->cascadeOnDelete();
            $table->string('event_name');
            $table->string('event_name_ar');
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->string('event_duration');
            $table->string('event_location');
            $table->string('event_link');
            $table->string('event_description');
            $table->string('event_description_ar');
            $table->string('event_image')->nullable();
            $table->string('event_status')->default('active');
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
