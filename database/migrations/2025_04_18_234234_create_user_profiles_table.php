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
        // Tabla de perfil extendido
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();

            // Redes sociales
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            // Configuración y privacidad
            $table->boolean('allow_mentions')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->string('timezone')->default('UTC');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
