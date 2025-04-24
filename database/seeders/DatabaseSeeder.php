<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Brewery;
use App\Models\BeerStyle;
use App\Models\Beer;
use App\Models\Location;
use App\Models\Post;
use App\Models\CheckIn;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follow;
use App\Models\Favorite;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (todo en uno).
     */
    public function run(): void
    {
        // Usuarios
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'ElAdmin',
            'email' => 'admin@beerfinder.com',
            'password' => Hash::make('password'),
            'bio' => 'Administrador de la plataforma BeerFinder',
            'location' => 'Madrid, España',
            'email_verified_at' => now(),
        ]);
        $users = User::factory()->count(30)->create();
        $allUsers = User::all();

        // Cervecerías
        $breweries = Brewery::factory()->count(30)->create();

        // Estilos de cerveza
        $beerStyles = BeerStyle::factory()->count(50)->create();

        // Cervezas
        $beers = Beer::factory()->count(200)->create();

        // Ubicaciones
        $locations = Location::factory()->count(20)->create();

        // Posts
        Post::factory(50)->create();
        foreach ($allUsers->take(5) as $user) {
            Post::factory(3)->forUser($user)->forBeer($beers->random())->create();
            Post::factory()->forUser($user)->forBeer($beers->random())->withoutRating()->create();
            Post::factory()->forUser($user)->forBeer($beers->random())->withoutLocation()->create();
        }
        $popularBeers = $beers->random(min(3, $beers->count()));
        foreach ($popularBeers as $beer) {
            Post::factory(5)->forBeer($beer)->create();
        }
        if (!$locations->isEmpty()) {
            foreach ($locations->take(5) as $location) {
                Post::factory(2)->state([
                    'location_id' => $location->id,
                    'user_id' => $allUsers->random()->id,
                    'beer_id' => $beers->random()->id,
                ])->create();
            }
        }
        $posts = Post::all();

        // Check-ins (uno por cada post)
        foreach ($posts as $post) {
            CheckIn::factory()->state([
                'post_id' => $post->id,
                'user_id' => $post->user_id,
                'beer_id' => $post->beer_id,
                'location_id' => $post->location_id,
            ])->create();
        }

        // Comentarios
        Comment::factory()->count(100)->create();

        // Likes
        Like::factory()->count(50)->create();

        // Follows
        Follow::factory()->count(50)->create();

        // Favoritos
        Favorite::factory()->count(30)->create();

        // Notificaciones
        Notification::create([
            'user_id' => $admin->id,
            'from_user_id' => $allUsers->random()->id,
            'type' => 'system',
            'related_id' => 1,
            'is_read' => true,
            'read_at' => now()->subDays(5),
        ]);
        Notification::create([
            'user_id' => $admin->id,
            'from_user_id' => $allUsers->random()->id,
            'type' => 'mention',
            'related_id' => 2,
            'is_read' => false,
            'read_at' => null,
        ]);
        foreach ($allUsers->take(10) as $user) {
            Notification::factory()->like()->forUser($user)->fromUser($allUsers->random())->read()->count(2)->create();
            Notification::factory()->comment()->forUser($user)->fromUser($allUsers->random())->unread()->count(3)->create();
        }
        Notification::factory()->count(50)->create();
    }
}
