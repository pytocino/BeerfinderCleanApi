<?php

namespace Database\Seeders;

use App\Models\CheckIn;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $checkIns = CheckIn::all();

        if ($users->isEmpty() || $checkIns->isEmpty()) {
            return;
        }

        foreach ($checkIns as $checkIn) {
            // Determinar cuántos likes recibirá este check-in (entre 0 y 10)
            $numLikes = rand(0, 10);
            $likedBy = collect();

            for ($i = 0; $i < $numLikes; $i++) {
                $user = $users->random();

                // Evitar likes duplicados y que el usuario de like a su propio check-in
                while ($likedBy->contains($user->id) || $user->id === $checkIn->user_id) {
                    $user = $users->random();

                    // Si ya se agotaron los usuarios disponibles, salir del bucle
                    if ($likedBy->count() >= $users->count() - 1) {
                        break;
                    }
                }

                $likedBy->push($user->id);

                Like::create([
                    'user_id' => $user->id,
                    'check_in_id' => $checkIn->id,
                    'created_at' => $checkIn->created_at->addMinutes(rand(30, 4320)), // Entre 30 min y 3 días después
                ]);
            }
        }
    }
}
