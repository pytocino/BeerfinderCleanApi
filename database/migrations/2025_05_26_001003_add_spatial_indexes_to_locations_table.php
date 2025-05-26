<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Añadir índices compuestos para optimizar consultas geoespaciales
            $table->index(['latitude', 'longitude'], 'locations_lat_lng_index');
            $table->index('type', 'locations_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations_lat_lng_index');
            $table->dropIndex('locations_type_index');
        });
    }
};
