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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que recibe la notificación
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Usuario que genera la acción (puede ser null para sistema)
            $table->string('type'); // like, comment, follow, mention, system, etc.
            $table->unsignedBigInteger('related_id')->nullable(); // ID de la entidad relacionada (like, comentario, follow, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable(); // Fecha/hora de lectura
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
