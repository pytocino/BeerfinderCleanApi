<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $hasPhoto = $this->faker->boolean(70);
        $additionalPhotos = $this->faker->boolean(40)
            ? $this->faker->images(3, 640, 480, false)
            : [];

        $userTags = $this->faker->boolean(40)
            ? $this->faker->randomElements(
                User::pluck('id')->toArray() ?: [User::factory()],
                $this->faker->numberBetween(1, 3)
            )
            : [];

        return [
            'user_id' => fn() => User::inRandomOrder()->first()?->id ?? User::factory(),
            'content' => $this->faker->paragraph(),
            'photo_url' => $hasPhoto ? $this->faker->imageUrl(640, 480, 'beer') : null,
            'additional_photos' => $additionalPhotos,
            'user_tags' => $userTags,
            'edited' => $this->faker->boolean(10),
        ];
    }
}
