<?php declare(strict_types=1);

namespace Nova\Filters;

use App\Enums\AnimeSeason;
use App\Models\Anime;
use App\Nova\Filters\AnimeSeasonFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use JoshGaber\NovaUnit\Exceptions\InvalidModelException;
use JoshGaber\NovaUnit\Filters\InvalidNovaFilterException;
use JoshGaber\NovaUnit\Filters\NovaFilterTest;
use Tests\TestCase;

/**
 * Class AnimeSeasonTest
 * @package Nova\Filters
 */
class AnimeSeasonTest extends TestCase
{
    use NovaFilterTest;
    use RefreshDatabase;
    use WithFaker;
    use WithoutEvents;

    /**
     * The Anime Season Filter shall be a select filter.
     *
     * @return void
     * @throws InvalidNovaFilterException
     */
    public function testSelectFilter()
    {
        static::novaFilter(AnimeSeasonFilter::class)
            ->assertSelectFilter();
    }

    /**
     * The Anime Season Filter shall have an option for each AnimeSeason instance.
     *
     * @return void
     * @throws InvalidNovaFilterException
     */
    public function testOptions()
    {
        $filter = static::novaFilter(AnimeSeasonFilter::class);

        foreach (AnimeSeason::getInstances() as $season) {
            $filter->assertHasOption($season->description);
        }
    }

    /**
     * The Anime Season Filter shall filter Anime By Season.
     *
     * @return void
     * @throws InvalidModelException
     * @throws InvalidNovaFilterException
     */
    public function testFilter()
    {
        $season = AnimeSeason::getRandomInstance();

        Anime::factory()->count($this->faker->randomDigitNotNull)->create();

        $filter = static::novaFilter(AnimeSeasonFilter::class);

        $response = $filter->apply(Anime::class, $season->value);

        $filteredAnimes = Anime::where('season', $season->value)->get();
        foreach ($filteredAnimes as $filteredAnime) {
            $response->assertContains($filteredAnime);
        }
        $response->assertCount($filteredAnimes->count());
    }
}
