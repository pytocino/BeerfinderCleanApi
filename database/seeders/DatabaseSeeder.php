<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\BeerStyle;
use App\Models\Beer;
use App\Models\Comment;
use App\Models\Location;
use App\Models\Post;
use App\Models\Like;
use App\Models\Favorite;
use App\Models\Follow;
use App\Models\CheckIn;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (solo lo básico).
     */
    public function run(): void
    {
        // Usuario admin (crea también su perfil)
        $admin = User::factory()->create([
            'name' => 'Admin',
            'username' => 'ElAdmin',
            'email' => 'admin@beerfinder.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'profile_picture' => null,
            'last_active_at' => now(),
            'is_admin' => true,

        ]);

        // Resto de usuarios (con perfil automático)
        User::factory()->count(30)->create();

        // Estilos de cerveza
        BeerStyle::factory()->count(30)->create();

        // Cervezas
        Beer::factory()->count(200)->create();

        // Ubicaciones
        Location::factory()->count(20)->create();

        // Posts
        Post::factory(300)->create();

        // Comentarios
        Comment::factory(600)->create();

        // Likes
        $users = User::pluck('id')->all();
        $posts = Post::pluck('id')->all();
        $likePairs = collect();

        while ($likePairs->count() < 1000) {
            $pair = [fake()->randomElement($users), fake()->randomElement($posts)];
            if (!$likePairs->contains($pair)) {
                $likePairs->push($pair);
            }
        }

        foreach ($likePairs as [$userId, $postId]) {
            Like::factory()->create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
        }

        // Favoritos
        Favorite::factory(200)->create();

        // Follows
        Follow::factory(200)->create();

        // Check-ins
        CheckIn::factory(400)->create();

        // Mensajes
        Message::factory(200)->create();

        // Conversaciones (chats)
        Conversation::factory()->count(50)->create();

        // Notificaciones (una por usuario, post y comentario)
        foreach (User::all() as $user) {
            Notification::factory()->create([
                'notifiable_id' => $user->id,
                'notifiable_type' => User::class,
                'type' => 'App\\Notifications\\GenericNotification',
                'data' => json_encode([
                    'message' => "¡Bienvenido {$user->name}!",
                ]),
            ]);
        }

        foreach (Post::all() as $post) {
            Notification::factory()->create([
                'notifiable_id' => $post->user_id,
                'notifiable_type' => User::class,
                'type' => 'App\\Notifications\\PostNotification',
                'data' => json_encode([
                    'message' => "Tu post #{$post->id} ha sido publicado.",
                    'post_id' => $post->id,
                ]),
            ]);
        }
        foreach (Comment::all() as $comment) {
            $post = $comment->post;
            if ($post && $post->user_id !== $comment->user_id) {
                Notification::factory()->create([
                    'notifiable_id' => $post->user_id,
                    'notifiable_type' => User::class,
                    'type' => 'App\\Notifications\\CommentNotification',
                    'data' => json_encode([
                        'message' => "{$comment->user->name} ha comentado tu post #{$post->id}.",
                        'comment_id' => $comment->id,
                        'post_id' => $post->id,
                    ]),
                ]);
            }
        }
    }
}
