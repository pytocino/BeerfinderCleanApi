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
        Schema::create('beers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('brewery_id')->constrained();
            $table->foreignId('style_id')->constrained('beer_styles');
            $table->decimal('abv', 4, 2)->nullable();
            $table->integer('ibu')->nullable();
            $table->string('color')->nullable();
            $table->string('label_image_url')->nullable();
            $table->string('package_type')->nullable();
            $table->string('availability')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('collaboration')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->year('first_brewed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beers');
    }
};
