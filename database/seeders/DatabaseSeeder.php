<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario admin visible para pruebas
        $admin = \App\Models\User::create([
            'name' => 'Admin Test',
            'username' => 'admin', // <-- Añade esto
            'email' => 'admin@beerfinder.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Perfiles de usuario
        \App\Models\User::factory(9)->create(); // 9 normales + 1 admin = 10
        \App\Models\UserProfile::factory(10)->create();

        // Asignar un perfil aleatorio al admin
        $profile = \App\Models\UserProfile::inRandomOrder()->first();
        if ($profile) {
            $profile->user_id = $admin->id;
            $profile->save();
        }

        // Catálogos y entidades principales
        \App\Models\BeerStyle::factory(8)->create();
        \App\Models\Brewery::factory(5)->create();
        \App\Models\Beer::factory(20)->create();
        \App\Models\Location::factory(8)->create();

        // Relación cervezas-lugares
        \App\Models\BeerLocation::factory(20)->create();

        // Publicaciones y comentarios
        \App\Models\Post::factory(20)->create();
        \App\Models\Comment::factory(40)->create();

        // Follows únicos
        $userIds = \App\Models\User::pluck('id')->toArray();
        $follows = [];
        foreach ($userIds as $follower) {
            $possible = array_diff($userIds, [$follower]);
            if (count($possible) < 2) continue;
            $followingIds = collect($possible)->random(2);
            foreach ($followingIds as $following) {
                $follows[] = [
                    'follower_id' => $follower,
                    'following_id' => $following,
                    'status' => fake()->randomElement(['pending', 'accepted', 'rejected']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        \App\Models\Follow::insert($follows);

        // Favoritos únicos
        $beerIds = \App\Models\Beer::pluck('id')->toArray();
        $locationIds = \App\Models\Location::pluck('id')->toArray();
        $favorites = [];
        foreach ($userIds as $userId) {
            if ($beerIds) {
                $beerId = collect($beerIds)->random();
                $favorites[] = [
                    'user_id' => $userId,
                    'favorable_id' => $beerId,
                    'favorable_type' => 'App\\Models\\Beer',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($locationIds) {
                $locationId = collect($locationIds)->random();
                $favorites[] = [
                    'user_id' => $userId,
                    'favorable_id' => $locationId,
                    'favorable_type' => 'App\\Models\\Location',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        \App\Models\Favorite::insert($favorites);

        // Likes únicos
        $postIds = \App\Models\Post::pluck('id')->toArray();
        $commentIds = \App\Models\Comment::pluck('id')->toArray();
        $likes = [];
        foreach ($userIds as $userId) {
            if ($postIds) {
                $postId = collect($postIds)->random();
                $likes[] = [
                    'user_id' => $userId,
                    'likeable_id' => $postId,
                    'likeable_type' => 'App\\Models\\Post',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($commentIds) {
                $commentId = collect($commentIds)->random();
                $likes[] = [
                    'user_id' => $userId,
                    'likeable_id' => $commentId,
                    'likeable_type' => 'App\\Models\\Comment',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if ($beerIds) {
                $beerId = collect($beerIds)->random();
                $likes[] = [
                    'user_id' => $userId,
                    'likeable_id' => $beerId,
                    'likeable_type' => 'App\\Models\\Beer',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        \App\Models\Like::insert($likes);

        // Beer reviews únicos
        $postIds = \App\Models\Post::pluck('id')->toArray();
        $servingTypes = ['bottle', 'can', 'draft', 'growler', 'taster', 'crowler'];
        $currencies = ['USD', 'EUR', 'GBP', 'MXN', 'BRL'];
        $beerReviews = [];
        foreach ($userIds as $userId) {
            if ($beerIds) {
                $beerId = collect($beerIds)->random();
                $beerReviews[] = [
                    'user_id' => $userId,
                    'beer_id' => $beerId,
                    'location_id' => $locationIds ? collect($locationIds)->random() : null,
                    'post_id' => $postIds ? collect($postIds)->random() : null,
                    'rating' => fake()->randomFloat(1, 1, 5),
                    'review_text' => fake()->optional(0.7)->paragraph(),
                    'serving_type' => fake()->randomElement($servingTypes),
                    'purchase_price' => fake()->optional(0.5)->randomFloat(2, 1, 20),
                    'purchase_currency' => fake()->randomElement($currencies),
                    'is_public' => fake()->boolean(80),
                    'avg_rating' => null,
                    'ratings_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        \App\Models\BeerReview::insert($beerReviews);

        // Reports
        \App\Models\Report::factory(15)->create();

        // Conversaciones y participantes
        \App\Models\Conversation::factory(8)->create();
        $conversationIds = \App\Models\Conversation::pluck('id')->toArray();
        $roles = ['member', 'admin', 'owner'];
        $conversationUsers = [];
        foreach ($conversationIds as $conversationId) {
            $members = collect($userIds)->random(rand(2, min(4, count($userIds))));
            foreach ($members as $userId) {
                $conversationUsers[] = [
                    'conversation_id' => $conversationId,
                    'user_id' => $userId,
                    'last_read_at' => now(),
                    'is_muted' => fake()->boolean(20),
                    'joined_at' => now(),
                    'left_at' => null,
                    'role' => fake()->randomElement($roles),
                    'can_add_members' => fake()->boolean(30),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        \App\Models\ConversationUser::insert($conversationUsers);

        // Mensajes
        \App\Models\Message::factory(60)->create();

        // Transacciones y sus ítems
        \App\Models\Transaction::factory(16)->create();
        \App\Models\TransactionItem::factory(32)->create();

        // Notificaciones
        \App\Models\Notification::factory(30)->create();
    }
}
