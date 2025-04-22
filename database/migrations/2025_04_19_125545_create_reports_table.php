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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reportable'); // reportable_type y reportable_id
            $table->enum('reason', [
                'spam',
                'offensive',
                'inappropriate',
                'harassment',
                'fake',
                'copyright',
                'other'
            ]);
            $table->text('details')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'rejected', 'actioned'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('public')->default(false); // Si el reporte es visible para el usuario reportado
            $table->string('screenshot_url')->nullable(); // Evidencia opcional
            $table->ipAddress('ip_address')->nullable(); // IP del usuario que reporta
            $table->string('user_agent')->nullable(); // User agent del navegador
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
