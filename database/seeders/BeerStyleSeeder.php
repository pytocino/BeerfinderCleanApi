<?php

namespace Database\Seeders;

use App\Models\BeerStyle;
use Illuminate\Database\Seeder;

class BeerStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $styles = [
            [
                'name' => 'IPA',
                'description' => 'India Pale Ale, caracterizada por su alto contenido de lúpulo y amargor.',
            ],
            [
                'name' => 'Lager',
                'description' => 'Cerveza de fermentación baja, clara y refrescante.',
            ],
            [
                'name' => 'Stout',
                'description' => 'Cerveza oscura hecha con malta tostada, con sabores a café y chocolate.',
            ],
            [
                'name' => 'Pilsner',
                'description' => 'Lager pálida y refrescante originaria de la República Checa.',
            ],
            [
                'name' => 'Wheat Beer',
                'description' => 'Cerveza elaborada con una proporción significativa de trigo.',
            ],
            [
                'name' => 'Porter',
                'description' => 'Cerveza oscura con sabores a malta tostada y chocolate.',
            ],
            [
                'name' => 'Pale Ale',
                'description' => 'Cerveza de fermentación alta con lúpulos aromáticos.',
            ],
            [
                'name' => 'Belgian Tripel',
                'description' => 'Cerveza belga fuerte y compleja con notas especiadas.',
            ],
            [
                'name' => 'Sour Beer',
                'description' => 'Cerveza ácida con sabores complejos y refrescantes.',
            ],
            [
                'name' => 'Amber Ale',
                'description' => 'Cerveza de color ámbar con equilibrio entre malta y lúpulo.',
            ],
        ];

        foreach ($styles as $style) {
            BeerStyle::create($style);
        }
    }
}
