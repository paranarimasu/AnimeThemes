<?php declare(strict_types=1);

namespace Nova\Lenses;

use App\Enums\ResourceSite;
use App\Models\Anime;
use App\Models\ExternalResource;
use App\Nova\Filters\AnimeSeasonFilter;
use App\Nova\Filters\AnimeYearFilter;
use App\Nova\Filters\CreatedEndDateFilter;
use App\Nova\Filters\CreatedStartDateFilter;
use App\Nova\Filters\DeletedEndDateFilter;
use App\Nova\Filters\DeletedStartDateFilter;
use App\Nova\Filters\UpdatedEndDateFilter;
use App\Nova\Filters\UpdatedStartDateFilter;
use App\Nova\Lenses\AnimeAnilistResourceLens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use JoshGaber\NovaUnit\Exceptions\InvalidModelException;
use JoshGaber\NovaUnit\Fields\FieldNotFoundException;
use JoshGaber\NovaUnit\Lenses\InvalidNovaLensException;
use JoshGaber\NovaUnit\Lenses\NovaLensTest;
use Tests\TestCase;

/**
 * Class AnimeAnilistResourceTest
 * @package Nova\Lenses
 */
class AnimeAnilistResourceTest extends TestCase
{
    use NovaLensTest;
    use RefreshDatabase;
    use WithFaker;
    use WithoutEvents;

    /**
     * The Anime Anilist Resource Lens shall contain Anime Fields.
     *
     * @return void
     * @throws InvalidNovaLensException
     */
    public function testFields()
    {
        $lens = static::novaLens(AnimeAnilistResourceLens::class);

        $lens->assertHasField(__('nova.id'));
        $lens->assertHasField(__('nova.name'));
        $lens->assertHasField(__('nova.slug'));
        $lens->assertHasField(__('nova.year'));
        $lens->assertHasField(__('nova.season'));
    }

    /**
     * The Anime Anilist Resource Lens fields shall be sortable.
     *
     * @return void
     * @throws FieldNotFoundException
     * @throws InvalidNovaLensException
     */
    public function testSortable()
    {
        $lens = static::novaLens(AnimeAnilistResourceLens::class);

        $lens->field(__('nova.id'))->assertSortable();
        $lens->field(__('nova.name'))->assertSortable();
        $lens->field(__('nova.slug'))->assertSortable();
        $lens->field(__('nova.year'))->assertSortable();
        $lens->field(__('nova.season'))->assertSortable();
    }

    /**
     * The Anime Anilist Resource Lens shall contain Anime Filters.
     *
     * @return void
     * @throws InvalidNovaLensException
     */
    public function testFilters()
    {
        $lens = static::novaLens(AnimeAnilistResourceLens::class);

        $lens->assertHasFilter(AnimeSeasonFilter::class);
        $lens->assertHasFilter(AnimeYearFilter::class);
        $lens->assertHasFilter(CreatedStartDateFilter::class);
        $lens->assertHasFilter(CreatedEndDateFilter::class);
        $lens->assertHasFilter(UpdatedStartDateFilter::class);
        $lens->assertHasFilter(UpdatedEndDateFilter::class);
        $lens->assertHasFilter(DeletedStartDateFilter::class);
        $lens->assertHasFilter(DeletedEndDateFilter::class);
    }

    // TODO: testActions()

    /**
     * The Anime Anilist Resource Lens shall use the 'withFilters' request.
     *
     * @return void
     * @throws InvalidModelException
     * @throws InvalidNovaLensException
     */
    public function testWithFilters()
    {
        $lens = static::novaLens(AnimeAnilistResourceLens::class);

        $query = $lens->query(Anime::class);

        $query->assertWithFilters();
    }

    /**
     * The Anime Anilist Resource Lens shall use the 'withOrdering' request.
     *
     * @return void
     * @throws InvalidModelException
     * @throws InvalidNovaLensException
     */
    public function testWithOrdering()
    {
        $lens = static::novaLens(AnimeAnilistResourceLens::class);

        $query = $lens->query(Anime::class);

        $query->assertWithOrdering();
    }

    /**
     * The Anime Anilist Resource Lens shall filter Anime without an Anilist Resource.
     *
     * @return void
     * @throws InvalidModelException
     * @throws InvalidNovaLensException
     */
    public function testQuery()
    {
        Anime::factory()
            ->has(ExternalResource::factory()->count($this->faker->randomDigitNotNull))
            ->count($this->faker->randomDigitNotNull)
            ->create();

        $filteredAnimes = Anime::whereDoesntHave('externalResources', function (Builder $resourceQuery) {
            $resourceQuery->where('site', ResourceSite::ANILIST);
        })
        ->get();

        $lens = static::novaLens(AnimeAnilistResourceLens::class);

        $query = $lens->query(Anime::class);

        foreach ($filteredAnimes as $filteredAnime) {
            $query->assertContains($filteredAnime);
        }
        $query->assertCount($filteredAnimes->count());
    }
}
