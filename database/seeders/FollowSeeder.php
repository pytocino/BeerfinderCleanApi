<?php

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return;
        }

        foreach ($users as $user) {
            // Cada usuario seguirá entre 0 y 8 usuarios aleatoriamente
            $numFollowing = rand(0, 8);
            $following = collect();

            for ($i = 0; $i < $numFollowing; $i++) {
                $userToFollow = $users->random();

                // Evitar auto-seguimiento y seguimientos duplicados
                while ($userToFollow->id === $user->id || $following->contains($userToFollow->id)) {
                    $userToFollow = $users->random();

                    if ($following->count() >= $users->count() - 1) {
                        break;
                    }
                }

                $following->push($userToFollow->id);

                Follow::create([
                    'follower_id' => $user->id,
                    'following_id' => $userToFollow->id,
                ]);
            }
        }
    }
}
