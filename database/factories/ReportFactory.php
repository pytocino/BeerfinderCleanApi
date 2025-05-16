<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $reportableTypes = [
            'App\\Models\\Post',
            'App\\Models\\Comment',
            'App\\Models\\User',
            'App\\Models\\Beer',
        ];

        $reasons = [
            'spam',
            'offensive',
            'inappropriate',
            'harassment',
            'fake',
            'copyright',
            'other',
        ];

        $statuses = [
            'pending',
            'reviewed',
            'rejected',
            'actioned',
        ];

        $reportableType = $this->faker->randomElement($reportableTypes);
        $reportableModel = new $reportableType;
        $reportableId = $reportableModel->inRandomOrder()->first()?->id;
        // ...existing code...
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportableId,
            'reason' => $this->faker->randomElement($reasons),
            'details' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'reviewed_by' => $this->faker->boolean(30) ? (User::inRandomOrder()->first()?->id ?? null) : null,
            'admin_notes' => $this->faker->boolean(40) ? $this->faker->paragraph() : null,
            'resolved_at' => $this->faker->boolean(40) ? now()->subDays(rand(1, 30)) : null,
            'public' => $this->faker->boolean(10),
            'screenshot_url' => $this->faker->boolean(20) ? $this->faker->imageUrl(800, 600, 'evidence') : null,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
        ];
        // ...existing code...
    }
}
