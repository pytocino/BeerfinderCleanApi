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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $table->boolean('accepted')->default(true); // Para solicitudes privadas, true si está aceptado
            $table->timestamp('followed_at')->nullable(); // Fecha/hora en que empezó a seguir
            $table->timestamp('unfollowed_at')->nullable(); // Fecha/hora en que dejó de seguir (si aplica)
            $table->timestamps();

            // Asegurar que un usuario no pueda seguir a otro usuario más de una vez
            $table->unique(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
