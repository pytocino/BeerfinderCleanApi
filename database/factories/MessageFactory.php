<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $conversationId = \App\Models\Conversation::inRandomOrder()->first()?->id;
        $userId = \App\Models\User::inRandomOrder()->first()?->id;

        if (!$conversationId || !$userId) {
            throw new \Exception('No hay conversaciones o usuarios disponibles para crear mensajes.');
        }

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
            'conversation_id' => $conversationId,
            'user_id' => $userId,
            'content' => $this->faker->sentence(),
            'attachments' => $attachments,
            'reply_to' => null,
            'is_edited' => $this->faker->boolean(10),
            'read_at' => $this->faker->boolean(80) ? $this->faker->dateTimeThisYear() : null,
        ];
    }
}
