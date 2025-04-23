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
        BeerStyle::factory()->count(50)->create();
    }
}
