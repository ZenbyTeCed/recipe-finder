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
        Schema::create('recipe_nutrition_cache', function (Blueprint $table) {
            $table->id();
            $table->string('recipe_id')->unique();
            $table->string('recipe_name');
            $table->integer('cook_time')->nullable();
            $table->integer('calories')->default(0);
            $table->integer('protein')->default(0);
            $table->integer('carbs')->default(0);
            $table->integer('fat')->default(0);
            $table->integer('fiber')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_nutrition_cache');
    }
};
