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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('auth_papers')->nullable();
            $table->string('gallery_name');
            $table->string('gallery_name_ar');
            $table->text('gallery_description')->nullable();
            $table->text('gallery_description_ar')->nullable();
            $table->text('logo')->default('https://e7.pngegg.com/pngimages/1016/578/png-clipart-computer-icons-showroom-building-car-dealer-rectangle-orange.png');
            $table->text('images')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
