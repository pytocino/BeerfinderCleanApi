<?php

namespace Database\Seeders;

use App\Models\Beer;
use App\Models\Brewery;
use App\Models\BeerStyle;
use Illuminate\Database\Seeder;

class BeerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las cervecerías y estilos
        $breweries = Brewery::all();
        $styles = BeerStyle::all();

        // Crear algunas cervezas populares
        $beers = [
            [
                'name' => 'Mahou Clásica',
                'brewery_id' => $breweries->where('name', 'Cervecería Mahou')->first()->id,
                'style_id' => $styles->where('name', 'Lager')->first()->id,
                'abv' => 4.8,
                'ibu' => 20,
                'description' => 'Cerveza rubia tipo Lager, suave y refrescante.',
                'image_url' => 'https://example.com/beers/mahou_clasica.png',
            ],
            [
                'name' => 'Estrella Galicia Especial',
                'brewery_id' => $breweries->where('name', 'Estrella Galicia')->first()->id,
                'style_id' => $styles->where('name', 'Lager')->first()->id,
                'abv' => 5.5,
                'ibu' => 25,
                'description' => 'Cerveza premium con carácter atlántico.',
                'image_url' => 'https://example.com/beers/estrella_galicia.png',
            ],
            [
                'name' => 'La Virgen IPA',
                'brewery_id' => $breweries->where('name', 'La Virgen')->first()->id,
                'style_id' => $styles->where('name', 'IPA')->first()->id,
                'abv' => 6.5,
                'ibu' => 65,
                'description' => 'IPA con intenso aroma a lúpulo y notas cítricas.',
                'image_url' => 'https://example.com/beers/la_virgen_ipa.png',
            ],
        ];

        foreach ($beers as $beer) {
            Beer::create($beer);
        }

        // Crear cervezas aleatorias para cada cervecería
        foreach ($breweries as $brewery) {
            // Crear entre 2 y 5 cervezas por cervecería
            $numBeers = rand(2, 5);

            Beer::factory()
                ->count($numBeers)
                ->create([
                    'brewery_id' => $brewery->id,
                    'style_id' => $styles->random()->id,
                ]);
        }
    }
}
