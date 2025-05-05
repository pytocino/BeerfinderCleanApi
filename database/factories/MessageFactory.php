<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $hasAttachments = $this->faker->boolean(30);
        $attachments = [];
        if ($hasAttachments) {
            $types = ['jpg', 'png', 'pdf', 'mp4', 'mp3', 'docx'];
            $count = $this->faker->numberBetween(1, 3);
            for ($i = 0; $i < $count; $i++) {
                $ext = $this->faker->randomElement($types);
                $attachments[] = $this->faker->imageUrl(640, 480, 'abstract') . '.' . $ext;
            }
        }

        return [
            'conversation_id' => null, // Asignar en el seeder o test
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'content' => $this->faker->sentence(),
            'attachments' => $attachments,
            'reply_to' => null,
            'is_edited' => $this->faker->boolean(10),
            'read_at' => $this->faker->boolean(80) ? $this->faker->dateTimeThisYear() : null,
        ];
    }
}
