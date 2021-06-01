<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VideoOverlap;
use App\Enums\VideoSource;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Class VideoFactory
 * @package Database\Factories
 */
class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'basename' => Str::random(),
            'filename' => Str::random(),
            'path' => Str::random(),
            'size' => $this->faker->randomDigitNotZero(),
            'mimetype' => $this->faker->mimeType,
            'resolution' => $this->faker->randomDigitNotZero(),
            'nc' => $this->faker->boolean,
            'subbed' => $this->faker->boolean,
            'lyrics' => $this->faker->boolean,
            'uncen' => $this->faker->boolean,
            'source' => VideoSource::getRandomValue(),
            'overlap' => VideoOverlap::getRandomValue(),
        ];
    }
}
