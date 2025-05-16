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
        // Tabla pivot mejorada para participantes
        Schema::create('conversation_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('user_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('last_read_at')->nullable(); // Cuándo el usuario leyó la conversación
            $table->boolean('is_muted')->default(false); // Silenciar notificaciones
            $table->timestamp('joined_at')->default(now());
            $table->timestamp('left_at')->nullable(); // Si el usuario abandona la conversación
            $table->enum('role', ['member', 'admin', 'owner'])->default('member'); // Rol en el grupo
            $table->boolean('can_add_members')->default(false); // Permisos específicos
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_users');
    }
};
