<?php

namespace Database\Seeders;

use App\Models\Beer;
use App\Models\CheckIn;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $users = User::all();
        $beers = Beer::all();
        $locations = Location::all();

        // Asegurarnos de que hay suficientes registros
        if ($users->isEmpty() || $beers->isEmpty()) {
            return;
        }

        // Crear 50 check-ins aleatorios
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $beer = $beers->random();

            // 70% de probabilidad de incluir una ubicación
            $location = (rand(1, 10) <= 7) ? $locations->random() : null;

            CheckIn::create([
                'user_id' => $user->id,
                'beer_id' => $beer->id,
                'location_id' => $location ? $location->id : null,
                'rating' => rand(10, 50) / 10, // Valor entre 1.0 y 5.0
                'review' => "He probado la " . $beer->name . ". " . $this->getRandomReview(),
                'photo_url' => rand(0, 1) ? 'https://example.com/check-ins/photo' . rand(1, 20) . '.jpg' : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }

    /**
     * Obtiene una reseña aleatoria para un check-in.
     */
    private function getRandomReview(): string
    {
        $reviews = [
            'Muy buena cerveza, refrescante y con buen sabor.',
            'Me ha encantado, tiene un sabor equilibrado y un aroma fantástico.',
            'No está mal, pero esperaba más. Un poco aguada para mi gusto.',
            'Excelente cerveza con notas a cítricos y un amargor equilibrado.',
            'Demasiado amarga para mi gusto, pero bien elaborada.',
            'Una de las mejores que he probado este año. Recomendada.',
            'Buen equilibrio entre malta y lúpulo, muy bebible.',
            'No me ha convencido, demasiado dulce para mi gusto.',
            'Perfecta para el verano, ligera y refrescante.',
            'Increíble aroma y cuerpo. La recomiendo totalmente.'
        ];

        return $reviews[array_rand($reviews)];
    }
}
