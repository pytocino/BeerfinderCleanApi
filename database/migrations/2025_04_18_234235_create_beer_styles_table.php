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
        Schema::create('beer_styles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('color')->nullable();
            $table->decimal('abv_min', 4, 2)->nullable();
            $table->decimal('abv_max', 4, 2)->nullable();
            $table->integer('ibu_min')->nullable();
            $table->integer('ibu_max')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beer_styles');
    }
};
