<?php

namespace Database\Seeders;

use App\Models\Beer;
use Illuminate\Database\Seeder;

class BeerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Beer::factory()->count(200)->create();
    }
}
