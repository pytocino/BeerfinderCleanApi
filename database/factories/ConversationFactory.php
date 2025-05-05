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
        $types = [Conversation::TYPE_DIRECT, Conversation::TYPE_GROUP];
        $type = $this->faker->randomElement($types);

        // Simula settings de grupo solo si es grupo
        $groupSettings = $type === Conversation::TYPE_GROUP
            ? [
                'allow_invite' => $this->faker->boolean(80),
                'max_members' => $this->faker->numberBetween(5, 100),
                'description' => $this->faker->optional()->sentence(),
            ]
            : null;

        return [
            'title' => $type === Conversation::TYPE_GROUP ? $this->faker->words(3, true) : null,
            'type' => $type,
            'last_message_at' => $this->faker->optional(0.7)->dateTimeThisYear(),
            'created_by' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'image_url' => $type === Conversation::TYPE_GROUP ? $this->faker->optional()->imageUrl(400, 400, 'group') : null,
            'description' => $type === Conversation::TYPE_GROUP ? $this->faker->optional()->sentence() : null,
            'is_public' => $type === Conversation::TYPE_GROUP ? $this->faker->boolean(30) : false,
            'group_settings' => $groupSettings,
        ];
    }
}
