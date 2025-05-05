<?php

namespace Database\Factories;

use App\Models\ConversationUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationUserFactory extends Factory
{
    protected $model = ConversationUser::class;

    public function definition(): array
    {
        $roles = [
            ConversationUser::ROLE_MEMBER,
            ConversationUser::ROLE_ADMIN,
            ConversationUser::ROLE_OWNER,
        ];

        return [
            'conversation_id' => null, // Asignar en el seeder o test
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'last_read_at' => $this->faker->optional(0.7)->dateTimeThisYear(),
            'is_muted' => $this->faker->boolean(20),
            'joined_at' => $this->faker->dateTimeThisYear(),
            'left_at' => $this->faker->optional(0.1)->dateTimeThisYear(),
            'role' => $this->faker->randomElement($roles),
            'can_add_members' => $this->faker->boolean(30),
        ];
    }
}
