<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $types = ['bar', 'restaurant', 'store', 'brewery', 'other'];
        $statuses = ['active', 'temporarily_closed', 'permanently_closed'];
        $country = $this->faker->country();
        $city = $this->faker->city();

        // Horarios de apertura simulados para cada día
        $opening_hours = [];
        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $opening_hours[$day] = [
                [
                    'open' => '12:00',
                    'close' => '23:00'
                ]
            ];
        }

        return [
            'name' => $this->faker->company . ' ' . ucfirst($this->faker->word),
            'type' => $this->faker->randomElement($types),
            'description' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
            'opening_hours' => $opening_hours,
            'address' => $this->faker->streetAddress,
            'city' => $city,
            'country' => $country,
            'latitude' => $this->faker->latitude(-90, 90),
            'longitude' => $this->faker->longitude(-180, 180),
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'bars'),
            'cover_photo' => $this->faker->optional()->imageUrl(1200, 400, 'nightlife'),
            'website' => $this->faker->optional()->url(),
            'email' => $this->faker->optional()->companyEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'verified' => $this->faker->boolean(30),
        ];
    }
}
