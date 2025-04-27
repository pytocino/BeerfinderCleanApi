<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_id' => 1, // Cambia esto según el modelo que vayas a notificar
            'notifiable_type' => 'App\\Models\\User',
            'data' => json_encode([
                'message' => $this->faker->sentence(),
                'extra' => $this->faker->word(),
            ]),
            'read_at' => $this->faker->optional(0.3)->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
