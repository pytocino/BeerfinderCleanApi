<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationUserFactory extends Factory
{
    protected $model = ConversationUser::class;

    public function definition(): array
    {
        $roles = ['member', 'admin', 'owner'];

        return [
            'conversation_id' => Conversation::inRandomOrder()->first()?->id,
            'user_id' => User::inRandomOrder()->first()?->id,
            'last_read_at' => $this->faker->optional(0.7)->dateTimeThisYear(),
            'is_muted' => $this->faker->boolean(20),
            'joined_at' => $this->faker->dateTimeThisYear(),
            'left_at' => $this->faker->optional(0.1)->dateTimeThisYear(),
            'role' => $this->faker->randomElement($roles),
            'can_add_members' => $this->faker->boolean(30),
        ];
    }
}
