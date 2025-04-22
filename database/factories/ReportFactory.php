<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->text(),
            'user_id' => fake()->text(),
            'reportable_type' => fake()->text(),
            'reportable_id' => fake()->text(),
            'reason' => fake()->text(),
            'details' => $this->faker->paragraph(),
            'status' => fake()->text(),
            'reviewed_by' => fake()->text(),
            'admin_notes' => $this->faker->paragraph(),
            'resolved_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
