<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BrewerySeeder::class,
            BeerStyleSeeder::class,
            BeerSeeder::class,
            LocationSeeder::class,
            CheckInSeeder::class,
            CommentSeeder::class,
            LikeSeeder::class,
            FollowSeeder::class,
            FavoriteSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
