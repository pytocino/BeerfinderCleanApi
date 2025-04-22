<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Notification;
use App\Models\CheckIn;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar notificaciones para los likes
        $this->createLikeNotifications();

        // Generar notificaciones para los comentarios
        $this->createCommentNotifications();

        // Generar notificaciones para los seguidores nuevos
        $this->createFollowNotifications();
    }

    /**
     * Crea notificaciones para los likes
     */
    private function createLikeNotifications(): void
    {
        $likes = Like::with(['checkIn.beer', 'user'])->get();

        foreach ($likes as $like) {
            // Solo notificar al dueño del check-in
            if ($like->checkIn && $like->user_id !== $like->checkIn->user_id) {
                Notification::create([
                    'user_id' => $like->checkIn->user_id,
                    'from_user_id' => $like->user_id,
                    'type' => 'like',
                    'related_id' => $like->id,
                    'is_read' => (bool) rand(0, 1),
                    'created_at' => $like->created_at,
                    'data' => [
                        'check_in_id' => $like->checkIn->id,
                        'beer_name' => $like->checkIn->beer->name ?? 'una cerveza',
                        'beer_id' => $like->checkIn->beer->id ?? null
                    ]
                ]);
            }
        }
    }

    /**
     * Crea notificaciones para los comentarios
     */
    private function createCommentNotifications(): void
    {
        $comments = Comment::with(['checkIn.beer', 'user'])->get();

        foreach ($comments as $comment) {
            // Solo notificar al dueño del check-in
            if ($comment->checkIn && $comment->user_id !== $comment->checkIn->user_id) {
                Notification::create([
                    'user_id' => $comment->checkIn->user_id,
                    'from_user_id' => $comment->user_id,
                    'type' => 'comment',
                    'related_id' => $comment->id,
                    'is_read' => (bool) rand(0, 1),
                    'created_at' => $comment->created_at,
                    'data' => [
                        'check_in_id' => $comment->checkIn->id,
                        'beer_name' => $comment->checkIn->beer->name ?? 'una cerveza',
                        'beer_id' => $comment->checkIn->beer->id ?? null,
                        'comment_preview' => mb_substr($comment->content, 0, 50) . (strlen($comment->content) > 50 ? '...' : '')
                    ]
                ]);
            }
        }
    }

    /**
     * Crea notificaciones para los nuevos seguidores
     */
    private function createFollowNotifications(): void
    {
        $follows = Follow::with(['follower', 'following'])->get();

        foreach ($follows as $follow) {
            Notification::create([
                'user_id' => $follow->following_id,
                'from_user_id' => $follow->follower_id,
                'type' => 'follow',
                'related_id' => $follow->id,
                'is_read' => (bool) rand(0, 1),
                'created_at' => $follow->created_at,
                'data' => [
                    'follower_username' => $follow->follower->name ?? 'Usuario'
                ]
            ]);
        }
    }
}
