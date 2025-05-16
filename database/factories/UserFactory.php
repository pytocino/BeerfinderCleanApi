<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Contraseña por defecto
            'profile_picture' => $this->faker->optional()->imageUrl(200, 200, 'people'),
            'is_admin' => false,
            'last_active_at' => now(),
            'private_profile' => $this->faker->boolean(20),
            'status' => $this->faker->randomElement(['active', 'suspended', 'blocked']),
            'remember_token' => Str::random(10),
        ];
    }
}
