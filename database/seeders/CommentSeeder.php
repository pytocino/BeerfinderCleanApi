<?php

namespace Database\Seeders;

use App\Models\CheckIn;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $checkIns = CheckIn::all();

        if ($users->isEmpty() || $checkIns->isEmpty()) {
            return;
        }

        // Crear entre 1 y 3 comentarios para cada check-in
        foreach ($checkIns as $checkIn) {
            $numComments = rand(0, 3);

            for ($i = 0; $i < $numComments; $i++) {
                $user = $users->random();

                // Evitar que el usuario comente su propio check-in
                while ($user->id === $checkIn->user_id) {
                    $user = $users->random();
                }

                Comment::create([
                    'user_id' => $user->id,
                    'check_in_id' => $checkIn->id,
                    'content' => $this->getRandomComment($checkIn),
                    'created_at' => $checkIn->created_at->addHours(rand(1, 24)),
                ]);
            }
        }
    }

    /**
     * Obtiene un comentario aleatorio.
     */
    private function getRandomComment($checkIn): string
    {
        $comments = [
            'Yo también la he probado, ¡y es genial!',
            'Coincido contigo, es una de mis favoritas.',
            'Tengo que probarla, ¿dónde la compraste?',
            'No me convence tanto como a ti, pero está bien.',
            '¿Has probado la nueva versión? Es incluso mejor.',
            'Buen check-in, gracias por compartir.',
            'Me encanta esa cervecería, hacen cervezas increíbles.',
            'Yo la probaría con un buen queso, va genial.',
            '¿Qué otras de esa marca recomendarías?',
            'Buena elección, la próxima ronda la pago yo.',
        ];

        return $comments[array_rand($comments)];
    }
}
