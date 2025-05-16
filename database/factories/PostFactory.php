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
            ? array_map(fn() => $this->faker->imageUrl(640, 480, 'beer'), range(1, 3))
            : [];

        $userTags = $this->faker->boolean(40)
            ? $this->faker->randomElements(
                User::pluck('id')->toArray(),
                $this->faker->numberBetween(1, 3)
            )
            : [];

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'content' => $this->faker->paragraph(),
            'photo_url' => $hasPhoto ? $this->faker->imageUrl(640, 480, 'beer') : null,
            'additional_photos' => json_encode($additionalPhotos),
            'user_tags' => json_encode($userTags),
            'edited' => $this->faker->boolean(10),
        ];
    }
}
