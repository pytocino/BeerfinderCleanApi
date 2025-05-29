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
        Schema::table('user_profiles', function (Blueprint $table) {
            // Configuraciones de privacidad adicionales
            $table->boolean('show_online_status')->default(true)->after('allow_mentions');
            $table->boolean('share_location')->default(false)->after('show_online_status');
            
            // Notificaciones detalladas
            $table->boolean('notify_new_followers')->default(true)->after('email_notifications');
            $table->boolean('notify_likes')->default(true)->after('notify_new_followers');
            $table->boolean('notify_comments')->default(true)->after('notify_likes');
            $table->boolean('notify_mentions')->default(true)->after('notify_comments');
            $table->boolean('notify_following_posts')->default(true)->after('notify_mentions');
            $table->boolean('notify_recommendations')->default(true)->after('notify_following_posts');
            $table->boolean('notify_trends')->default(false)->after('notify_recommendations');
            $table->boolean('notify_direct_messages')->default(true)->after('notify_trends');
            $table->boolean('notify_group_messages')->default(true)->after('notify_direct_messages');
            $table->boolean('notify_events')->default(true)->after('notify_group_messages');
            $table->boolean('notify_updates')->default(true)->after('notify_events');
            $table->boolean('notify_security')->default(true)->after('notify_updates');
            $table->boolean('notify_promotions')->default(false)->after('notify_security');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'show_online_status',
                'share_location',
                'notify_new_followers',
                'notify_likes',
                'notify_comments',
                'notify_mentions',
                'notify_following_posts',
                'notify_recommendations',
                'notify_trends',
                'notify_direct_messages',
                'notify_group_messages',
                'notify_events',
                'notify_updates',
                'notify_security',
                'notify_promotions'
            ]);
        });
    }
};
