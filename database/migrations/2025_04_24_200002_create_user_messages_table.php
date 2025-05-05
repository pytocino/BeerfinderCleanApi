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
        // Tabla de mensajes mejorada
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // autor del mensaje
            $table->text('content');
            $table->json('attachments')->nullable(); // URLs de imágenes, archivos, etc.
            $table->foreignId('reply_to')->nullable()->constrained('user_messages')->nullOnDelete();
            $table->boolean('is_edited')->default(false);
            $table->softDeletes(); // Para "Eliminar para mí" o "Eliminar para todos"
            $table->timestamp('read_at')->nullable(); // para saber si fue leído
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_messages');
    }
};
