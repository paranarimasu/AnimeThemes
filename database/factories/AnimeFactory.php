<?php

namespace Database\Factories;

use App\Enums\AnimeSeason;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Anime::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => Str::slug($this->faker->words(3, true), '_'),
            'name' => $this->faker->words(3, true),
            'year' => intval($this->faker->year()),
            'season' => AnimeSeason::getRandomValue(),
            'synopsis' => $this->faker->text,
        ];
    }
}