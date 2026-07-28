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
         Schema::create('feedback', function (Blueprint $table) {
        $table->id('feedback_id');

        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->foreignId('recommendation_id')
              ->constrained('recommendations', 'recommendation_id')
              ->onDelete('cascade');

        $table->text('feedback_text')->nullable();
        $table->tinyInteger('rating');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
