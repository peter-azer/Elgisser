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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('auth_papers')->nullable();
            $table->string('artist_name')->nullable();
            $table->string('artist_name_ar')->nullable();
            $table->string('experience')->nullable();
            $table->string('experience_ar')->nullable();
            $table->text('artist_bio')->nullable();
            $table->text('artist_bio_ar')->nullable();
            $table->string('artist_image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
