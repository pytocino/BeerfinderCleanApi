ers_table.php
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
            $table->foreignId('style_id')->constrained('beer_styles'); // Cambiado para referenciar a beer_styles
            $table->decimal('abv', 4, 2)->nullable(); // Alcohol by volume (%)
            $table->integer('ibu')->nullable();       // International Bitterness Units
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
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
