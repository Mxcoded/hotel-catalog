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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Room name
            $table->text('description'); // Room description
            $table->decimal('price', 8, 2); // Room price
            $table->json('features'); // Features stored in JSON format
            $table->boolean('available')->default(true); // Availability status
            $table->string('image_path')->nullable(); // Add this line for image path
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
