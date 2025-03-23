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
        Schema::create('art_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->double('price');
            $table->string('dimensions');
            $table->integer('quantity')->default(1);
            $table->boolean('one_of_a_kind')->default(false);
            $table->text('cover_image');
            $table->text('images')->nullable();
            $table->text('description')->nullable();
            $table->boolean('for_rent')->default(false);
            $table->double('rent_price')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('art_works');
    }
};
