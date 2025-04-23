.php
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('beer_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');

            // Contenido básico
            $table->text('review')->nullable();
            $table->decimal('rating', 2, 1)->nullable(); // Valoración de 0 a 5 con un decimal

            // Media/fotos
            $table->string('photo_url')->nullable();
            $table->json('additional_photos')->nullable(); // URLs adicionales en formato JSON

            // Datos específicos de la experiencia
            $table->string('serving_type')->nullable(); // Botella, lata, barril, etc.
            $table->decimal('purchase_price', 8, 2)->nullable();
            $table->string('purchase_currency', 3)->nullable();

            // Social
            $table->json('user_tags')->nullable(); // Usuarios etiquetados en JSON

            // Estadísticas (para consultas rápidas)
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);

            // Edición
            $table->boolean('edited')->default(false);
            $table->timestamp('edited_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
