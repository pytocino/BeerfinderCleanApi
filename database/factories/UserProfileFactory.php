<?php

namespace Database\Factories;

use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        $socials = [
            'instagram' => fake()->userName(),
            'twitter' => fake()->userName(),
            'facebook' => fake()->userName(),
        ];

        return [
            'bio' => fake()->paragraph(),
            'location' => fake()->city(),
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'website' => fake()->url(),
            'phone' => fake()->phoneNumber(),
            'instagram' => $socials['instagram'],
            'twitter' => $socials['twitter'],
            'facebook' => $socials['facebook'],
            'private_profile' => fake()->boolean(20),
            'allow_mentions' => fake()->boolean(90),
            'email_notifications' => fake()->boolean(80),
        ];
    }
}
