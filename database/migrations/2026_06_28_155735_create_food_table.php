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
         Schema::create('foods', function (Blueprint $table) {
        $table->id('food_id');

        $table->foreignId('restaurant_id')
              ->constrained('restaurants', 'restaurant_id')
              ->onDelete('cascade');

        $table->string('food_name');
        $table->string('food_category');
        $table->text('food_description')->nullable();
        $table->decimal('price', 8, 2);
        $table->decimal('rating', 2, 1)->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
