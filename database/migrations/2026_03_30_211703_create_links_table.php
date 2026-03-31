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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('title', 100);
            $table->string('url', 2048)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('image_path')->nullable();
            $table->string('bg_color', 7)->nullable();
            $table->string('text_color', 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position');
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
