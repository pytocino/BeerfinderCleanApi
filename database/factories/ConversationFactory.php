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
        return [];
    }

    public function configure()
    {
        return $this->afterCreating(function (Conversation $conversation) {
            // Añade 2 participantes aleatorios (no repetidos)
            $users = User::inRandomOrder()->limit(2)->pluck('id');
            $conversation->users()->attach($users);
        });
    }
}
