<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PodcastFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph,
            'image_url' => $this->faker->imageUrl(640, 480, 'podcast', true),
            'author_name' => $this->faker->name,
            'language' => $this->faker->randomElement(['en', 'es', 'fr', 'pt']),
            'status' => $this->faker->randomElement(['published', 'draft', 'archived']),
            'episode_count' => $this->faker->numberBetween(0, 100),
            'published_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }
}
