<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDuplicateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clean-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina notificaciones duplicadas basándose en tipo, usuario y contenido';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpieza de notificaciones duplicadas...');

        // Contar notificaciones duplicadas antes de la limpieza
        $totalBefore = DB::table('notifications')->count();
        $this->info("Total de notificaciones antes de la limpieza: {$totalBefore}");

        // Eliminar notificaciones duplicadas de seguimiento
        $followDuplicates = $this->cleanFollowNotifications();
        
        // Eliminar notificaciones duplicadas de likes en posts
        $likeDuplicates = $this->cleanLikeNotifications();
        
        // Eliminar notificaciones duplicadas de comentarios
        $commentDuplicates = $this->cleanCommentNotifications();
        
        // Eliminar notificaciones duplicadas de likes en comentarios
        $commentLikeDuplicates = $this->cleanCommentLikeNotifications();

        $totalAfter = DB::table('notifications')->count();
        $totalRemoved = $totalBefore - $totalAfter;

        $this->info("Notificaciones de seguimiento duplicadas eliminadas: {$followDuplicates}");
        $this->info("Notificaciones de likes duplicadas eliminadas: {$likeDuplicates}");
        $this->info("Notificaciones de comentarios duplicadas eliminadas: {$commentDuplicates}");
        $this->info("Notificaciones de likes en comentarios duplicadas eliminadas: {$commentLikeDuplicates}");
        $this->info("Total de notificaciones después de la limpieza: {$totalAfter}");
        $this->info("Total de notificaciones eliminadas: {$totalRemoved}");
        
        $this->info('Limpieza completada exitosamente.');
    }

    private function cleanFollowNotifications(): int
    {
        // Encuentra y elimina notificaciones de seguimiento duplicadas
        $duplicateIds = DB::select("
            SELECT n1.id
            FROM notifications n1
            INNER JOIN notifications n2 
                ON n1.notifiable_id = n2.notifiable_id 
                AND n1.type = n2.type
                AND JSON_EXTRACT(n1.data, '$.user_id') = JSON_EXTRACT(n2.data, '$.user_id')
                AND n1.id > n2.id
            WHERE n1.type = 'App\\\\Notifications\\\\UserFollowedNotification'
        ");

        $ids = collect($duplicateIds)->pluck('id')->toArray();
        
        if (!empty($ids)) {
            DB::table('notifications')->whereIn('id', $ids)->delete();
        }

        return count($ids);
    }

    private function cleanLikeNotifications(): int
    {
        // Encuentra y elimina notificaciones de likes duplicadas
        $duplicateIds = DB::select("
            SELECT n1.id
            FROM notifications n1
            INNER JOIN notifications n2 
                ON n1.notifiable_id = n2.notifiable_id 
                AND n1.type = n2.type
                AND JSON_EXTRACT(n1.data, '$.user_id') = JSON_EXTRACT(n2.data, '$.user_id')
                AND JSON_EXTRACT(n1.data, '$.post_id') = JSON_EXTRACT(n2.data, '$.post_id')
                AND n1.id > n2.id
            WHERE n1.type = 'App\\\\Notifications\\\\UserLikedPost'
        ");

        $ids = collect($duplicateIds)->pluck('id')->toArray();
        
        if (!empty($ids)) {
            DB::table('notifications')->whereIn('id', $ids)->delete();
        }

        return count($ids);
    }

    private function cleanCommentNotifications(): int
    {
        // Encuentra y elimina notificaciones de comentarios duplicadas
        $duplicateIds = DB::select("
            SELECT n1.id
            FROM notifications n1
            INNER JOIN notifications n2 
                ON n1.notifiable_id = n2.notifiable_id 
                AND n1.type = n2.type
                AND JSON_EXTRACT(n1.data, '$.user_id') = JSON_EXTRACT(n2.data, '$.user_id')
                AND JSON_EXTRACT(n1.data, '$.post_id') = JSON_EXTRACT(n2.data, '$.post_id')
                AND n1.id > n2.id
            WHERE n1.type = 'App\\\\Notifications\\\\UserCommentedPost'
        ");

        $ids = collect($duplicateIds)->pluck('id')->toArray();
        
        if (!empty($ids)) {
            DB::table('notifications')->whereIn('id', $ids)->delete();
        }

        return count($ids);
    }

    private function cleanCommentLikeNotifications(): int
    {
        // Encuentra y elimina notificaciones de likes en comentarios duplicadas
        $duplicateIds = DB::select("
            SELECT n1.id
            FROM notifications n1
            INNER JOIN notifications n2 
                ON n1.notifiable_id = n2.notifiable_id 
                AND n1.type = n2.type
                AND JSON_EXTRACT(n1.data, '$.user_id') = JSON_EXTRACT(n2.data, '$.user_id')
                AND JSON_EXTRACT(n1.data, '$.comment_id') = JSON_EXTRACT(n2.data, '$.comment_id')
                AND n1.id > n2.id
            WHERE n1.type = 'App\\\\Notifications\\\\UserLikedComment'
        ");

        $ids = collect($duplicateIds)->pluck('id')->toArray();
        
        if (!empty($ids)) {
            DB::table('notifications')->whereIn('id', $ids)->delete();
        }

        return count($ids);
    }
}
