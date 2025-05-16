<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'bio' => $this->faker->optional()->text(200),
            'location' => $this->faker->optional()->city(),
            'birthdate' => $this->faker->optional()->date('Y-m-d', '2005-12-31'),
            'website' => $this->faker->optional()->url(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'instagram' => $this->faker->optional()->userName(),
            'twitter' => $this->faker->optional()->userName(),
            'facebook' => $this->faker->optional()->userName(),
            'allow_mentions' => $this->faker->boolean(90),
            'email_notifications' => $this->faker->boolean(80),
            'timezone' => $this->faker->timezone() ?? 'UTC',
        ];
    }
}
