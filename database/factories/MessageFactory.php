<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Evitar que el remitente y el receptor sean el mismo usuario
        do {
            $sender_id = User::query()->inRandomOrder()->value('id') ?? User::factory();
            $receiver_id = User::query()->inRandomOrder()->value('id') ?? User::factory();
        } while ($sender_id === $receiver_id);

        return [
            'conversation_id' => Conversation::query()->inRandomOrder()->value('id') ?? Conversation::factory(),
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'content' => $this->faker->sentence(12),
            'is_read' => $this->faker->boolean(30), // 30% probabilidad de estar leído
        ];
    }
}
