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
        Schema::create('restaurants', function (Blueprint $table) {
        $table->id('restaurant_id');
        $table->foreignId('admin_id')->constrained('admins', 'admin_id')->onDelete('cascade');
        $table->string('restaurant_name');
        $table->string('contact_number');
        $table->text('address');
        $table->decimal('rating', 2, 1)->nullable();
        $table->string('price_level');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
