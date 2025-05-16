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
        Schema::create('beer_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('beer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained('user_posts')->nullOnDelete();
            $table->decimal('rating', 3, 1); // Cambiado: mayor precisión y NOT NULL
            $table->text('review_text')->nullable();
            $table->string('serving_type')->nullable();
            $table->decimal('purchase_price', 8, 2)->nullable();
            $table->string('purchase_currency', 3)->nullable();
            $table->boolean('is_public')->default(true); // Añadido: controlar visibilidad
            $table->decimal('avg_rating', 3, 2)->nullable();
            $table->integer('ratings_count')->default(0);
            $table->timestamps();

            // Restricción para evitar reviews duplicados del mismo usuario a la misma cerveza
            $table->unique(['user_id', 'beer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beer_reviews');
    }
};
