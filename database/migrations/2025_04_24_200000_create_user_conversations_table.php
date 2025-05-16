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
        // Tabla de conversaciones mejorada
        Schema::create('user_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Para grupos o tema de conversación
            $table->enum('type', ['direct', 'group'])->default('direct');
            $table->timestamp('last_message_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('image_url')->nullable(); // Foto del grupo
            $table->text('description')->nullable(); // Descripción del grupo
            $table->boolean('is_public')->default(false); // Si el grupo puede ser descubierto
            $table->json('group_settings')->nullable(); // Configuraciones adicionales del grupo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_conversations');
    }
};
