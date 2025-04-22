<?php

namespace Database\Seeders;

use App\Models\Brewery;
use Illuminate\Database\Seeder;

class BrewerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunas cervecerías famosas
        $breweries = [
            [
                'name' => 'Cervecería Mahou',
                'country' => 'España',
                'city' => 'Madrid',
                'address' => 'Paseo Imperial, 32',
                'latitude' => 40.4078,
                'longitude' => -3.7097,
                'description' => 'Fundada en 1890, Mahou es una de las cervecerías más antiguas de España.',
                'logo_url' => 'https://example.com/logos/mahou.png',
                'website' => 'https://www.mahou.es',
            ],
            [
                'name' => 'Estrella Galicia',
                'country' => 'España',
                'city' => 'A Coruña',
                'address' => 'Av. de Arteixo, 33',
                'latitude' => 43.3623,
                'longitude' => -8.4100,
                'description' => 'Cervecería familiar fundada en 1906 en Galicia.',
                'logo_url' => 'https://example.com/logos/estrella_galicia.png',
                'website' => 'https://estrellagalicia.es',
            ],
            [
                'name' => 'La Virgen',
                'country' => 'España',
                'city' => 'Madrid',
                'address' => 'Av. Real de Pinto, 48',
                'latitude' => 40.3450,
                'longitude' => -3.6843,
                'description' => 'Cervecería artesanal madrileña fundada en 2011.',
                'logo_url' => 'https://example.com/logos/la_virgen.png',
                'website' => 'https://cervezaslavirgen.com',
            ],
        ];

        foreach ($breweries as $brewery) {
            Brewery::create($brewery);
        }

        // Crear 15 cervecerías aleatorias adicionales
        Brewery::factory()->count(15)->create();
    }
}
