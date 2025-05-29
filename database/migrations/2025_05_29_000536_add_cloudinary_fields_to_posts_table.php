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
        Schema::table('user_posts', function (Blueprint $table) {
            $table->string('photo_public_id')->nullable()->after('photo_url');
            $table->json('additional_photos_public_ids')->nullable()->after('additional_photos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_posts', function (Blueprint $table) {
            $table->dropColumn(['photo_public_id', 'additional_photos_public_ids']);
        });
    }
};
