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
            Report::REASON_SPAM,
            Report::REASON_OFFENSIVE,
            Report::REASON_INAPPROPRIATE,
            Report::REASON_HARASSMENT,
            Report::REASON_FAKE,
            Report::REASON_COPYRIGHT,
            Report::REASON_OTHER,
        ];

        $statuses = [
            Report::STATUS_PENDING,
            Report::STATUS_REVIEWED,
            Report::STATUS_REJECTED,
            Report::STATUS_ACTIONED,
        ];

        return [
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'reportable_type' => $this->faker->randomElement($reportableTypes),
            'reportable_id' => $this->faker->numberBetween(1, 100),
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
    }
}
