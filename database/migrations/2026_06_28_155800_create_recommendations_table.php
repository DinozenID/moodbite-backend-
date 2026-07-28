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
        Schema::create('recommendations', function (Blueprint $table) {
        $table->id('recommendation_id');

        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->foreignId('food_id')
              ->constrained('foods', 'food_id')
              ->onDelete('cascade');

        $table->foreignId('mood_id')
              ->constrained('moods', 'mood_id')
              ->onDelete('cascade');

        $table->decimal('budget', 8, 2);
        $table->decimal('recommendation_score', 5, 2)->nullable();

        $table->timestamp('recommended_at')->useCurrent();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
