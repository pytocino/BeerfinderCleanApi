<?php

namespace Database\Factories;

use App\Models\BeerReview;
use App\Models\User;
use App\Models\Beer;
use App\Models\Location;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeerReviewFactory extends Factory
{
    protected $model = BeerReview::class;

    public function definition(): array
    {
        $servingTypes = BeerReview::$servingTypes ?? ['bottle', 'can', 'draft', 'growler', 'taster', 'crowler'];
        $currencies = ['USD', 'EUR', 'GBP', 'MXN', 'BRL'];

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'beer_id' => Beer::inRandomOrder()->first()?->id,
            'location_id' => Location::inRandomOrder()->first()?->id,
            'post_id' => Post::inRandomOrder()->first()?->id,
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'review_text' => $this->faker->optional(0.7)->paragraph(),
            'serving_type' => $this->faker->randomElement($servingTypes),
            'purchase_price' => $this->faker->optional(0.5)->randomFloat(2, 1, 20),
            'purchase_currency' => $this->faker->randomElement($currencies),
            'is_public' => $this->faker->boolean(80),
            'avg_rating' => null,
            'ratings_count' => 0,
        ];
    }
}
