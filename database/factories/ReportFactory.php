<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        // Tipos de contenido que pueden ser reportados
        $reportableTypes = [
            'App\\Models\\Post',
            'App\\Models\\Comment',
            'App\\Models\\User',
            'App\\Models\\Beer',
        ];

        // Razones válidas para reportar (según el enum en la migración)
        $reasons = [
            'spam',
            'offensive',
            'inappropriate',
            'harassment',
            'fake',
            'copyright',
            'other'
        ];

        // Estados válidos (según el enum en la migración)
        $statuses = ['pending', 'reviewed', 'rejected', 'actioned'];

        return [
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(), // Usuario que reporta
            'reportable_type' => $this->faker->randomElement($reportableTypes),
            'reportable_id' => $this->faker->numberBetween(1, 100),
            'reason' => $this->faker->randomElement($reasons),
            'details' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'reviewed_by' => fake()->boolean(30) ?
                (User::inRandomOrder()->first()?->id ?? null) : null,
            'admin_notes' => fake()->boolean(40) ? $this->faker->paragraph() : null,
            'resolved_at' => fake()->boolean(40) ? now()->subDays(rand(1, 30)) : null,
            'public' => fake()->boolean(10), // 10% son públicos
            'screenshot_url' => fake()->boolean(20) ? fake()->imageUrl(800, 600, 'evidence') : null,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
        ];
    }

    /**
     * Configurar como reporte pendiente.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'reviewed_by' => null,
            'admin_notes' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Configurar como reporte revisado.
     */
    public function reviewed(?User $reviewer): static
    {
        if (!$reviewer) {
            $reviewer = User::inRandomOrder()->first() ?? User::factory()->create();
        }

        return $this->state(fn(array $attributes) => [
            'status' => 'reviewed',
            'reviewed_by' => $reviewer->id,
            'admin_notes' => $this->faker->paragraph(),
            'resolved_at' => now()->subDays(rand(1, 10)),
        ]);
    }

    /**
     * Configurar como reporte rechazado.
     */
    public function rejected(?User $reviewer): static
    {
        if (!$reviewer) {
            $reviewer = User::inRandomOrder()->first() ?? User::factory()->create();
        }

        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'admin_notes' => 'No violation found. Report rejected.',
            'resolved_at' => now()->subDays(rand(1, 10)),
        ]);
    }

    /**
     * Configurar como reporte con acción tomada.
     */
    public function actioned(?User $reviewer): static
    {
        if (!$reviewer) {
            $reviewer = User::inRandomOrder()->first() ?? User::factory()->create();
        }

        return $this->state(fn(array $attributes) => [
            'status' => 'actioned',
            'reviewed_by' => $reviewer->id,
            'admin_notes' => 'Violation confirmed. Action taken.',
            'resolved_at' => now()->subDays(rand(1, 10)),
        ]);
    }
}
