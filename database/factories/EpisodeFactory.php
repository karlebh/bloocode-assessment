<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EpisodeFactory extends Factory
{

    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'podcast_id' => Podcast::inRandomOrder()->first()->id ?? Podcast::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'audio_url' => $this->faker->url,
            'duration' => $this->faker->time('H:i:s'),
            'episode_number' => $this->faker->numberBetween(1, 50),
            'summary' => $this->faker->paragraph,
            'release_date' => $this->faker->date(),
        ];
    }
}
