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
        Schema::create('user_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Relación con el post
            $table->text('content');
            $table->foreignId('parent_id')->nullable()->constrained('user_comments')->onDelete('cascade'); // Para respuestas/hilos
            $table->boolean('edited')->default(false); // Si el comentario fue editado
            $table->boolean('pinned')->default(false); // Si el comentario está fijado
            $table->timestamp('edited_at')->nullable();
            $table->integer('likes_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_comments');
    }
};
