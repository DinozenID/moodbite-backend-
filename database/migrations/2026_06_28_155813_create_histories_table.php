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
         Schema::create('histories', function (Blueprint $table) {
        $table->id('history_id');

        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->foreignId('food_id')
              ->constrained('foods', 'food_id')
              ->onDelete('cascade');

        $table->foreignId('restaurant_id')
              ->constrained('restaurants', 'restaurant_id')
              ->onDelete('cascade');

        $table->timestamp('viewed_at')->useCurrent();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
