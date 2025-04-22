<?php

namespace Database\Seeders;

use App\Models\Beer;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $beers = Beer::all();

        if ($users->isEmpty() || $beers->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Cada usuario marcará entre 0 y 5 cervezas como favoritas
            $numFavorites = rand(0, 5);
            $favoritedBeers = collect();

            for ($i = 0; $i < $numFavorites; $i++) {
                $beer = $beers->random();

                // Evitar duplicados
                while ($favoritedBeers->contains($beer->id)) {
                    $beer = $beers->random();

                    if ($favoritedBeers->count() >= $beers->count()) {
                        break;
                    }
                }

                $favoritedBeers->push($beer->id);

                Favorite::create([
                    'user_id' => $user->id,
                    'beer_id' => $beer->id,
                ]);
            }
        }
    }
}
