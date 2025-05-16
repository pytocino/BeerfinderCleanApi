<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        $types = ['direct', 'group'];
        $type = $this->faker->randomElement($types);

        // Simula settings de grupo solo si es grupo
        $groupSettings = $type === 'group'
            ? [
                'allow_invite' => $this->faker->boolean(80),
                'max_members' => $this->faker->numberBetween(5, 100),
                'description' => $this->faker->optional()->sentence(),
            ]
            : null;

        return [
            'title' => $type === 'group' ? $this->faker->words(3, true) : null,
            'type' => $type,
            'last_message_at' => $this->faker->optional(0.7)->dateTimeThisYear(),
            'created_by' => User::inRandomOrder()->first()?->id,
            'image_url' => $type === 'group' ? $this->faker->optional()->imageUrl(400, 400, 'group') : null,
            'description' => $type === 'group' ? $this->faker->optional()->sentence() : null,
            'is_public' => $type === 'group' ? $this->faker->boolean(30) : false,
            'group_settings' => $groupSettings,
        ];
    }
}
