<?php

namespace Tests\Unit\Nova\Lenses;

use App\Enums\ImageFacet;
use App\Models\Anime;
use App\Models\Image;
use App\Nova\Filters\AnimeSeasonFilter;
use App\Nova\Filters\AnimeYearFilter;
use App\Nova\Filters\CreatedEndDateFilter;
use App\Nova\Filters\CreatedStartDateFilter;
use App\Nova\Filters\DeletedEndDateFilter;
use App\Nova\Filters\DeletedStartDateFilter;
use App\Nova\Filters\UpdatedEndDateFilter;
use App\Nova\Filters\UpdatedStartDateFilter;
use App\Nova\Lenses\AnimeCoverLargeLens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use JoshGaber\NovaUnit\Lenses\NovaLensTest;
use Tests\TestCase;

class AnimeCoverLargeTest extends TestCase
{
    use NovaLensTest, RefreshDatabase, WithFaker, WithoutEvents;

    /**
     * The Anime Large Cover Lens shall contain Anime Fields.
     *
     * @return void
     */
    public function testFields()
    {
        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $lens->assertHasField(__('nova.id'));
        $lens->assertHasField(__('nova.name'));
        $lens->assertHasField(__('nova.slug'));
        $lens->assertHasField(__('nova.year'));
        $lens->assertHasField(__('nova.season'));
    }

    /**
     * The Anime Large Cover Lens fields shall be sortable.
     *
     * @return void
     */
    public function testSortable()
    {
        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $lens->field(__('nova.id'))->assertSortable();
        $lens->field(__('nova.name'))->assertSortable();
        $lens->field(__('nova.slug'))->assertSortable();
        $lens->field(__('nova.year'))->assertSortable();
        $lens->field(__('nova.season'))->assertSortable();
    }

    /**
     * The Anime Large Cover Lens shall contain Anime Filters.
     *
     * @return void
     */
    public function testFilters()
    {
        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $lens->assertHasFilter(AnimeSeasonFilter::class);
        $lens->assertHasFilter(AnimeYearFilter::class);
        $lens->assertHasFilter(CreatedStartDateFilter::class);
        $lens->assertHasFilter(CreatedEndDateFilter::class);
        $lens->assertHasFilter(UpdatedStartDateFilter::class);
        $lens->assertHasFilter(UpdatedEndDateFilter::class);
        $lens->assertHasFilter(DeletedStartDateFilter::class);
        $lens->assertHasFilter(DeletedEndDateFilter::class);
    }

    /**
     * The Anime Large Cover Lens shall contain no Actions.
     *
     * @return void
     */
    public function testActions()
    {
        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $lens->assertHasNoActions();
    }

    /**
     * The Anime Large Cover Lens shall use the 'withFilters' request.
     *
     * @return void
     */
    public function testWithFilters()
    {
        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $query = $lens->query(Anime::class);

        $query->assertWithFilters();
    }

    /**
     * The Anime Large Cover Lens shall use the 'withOrdering' request.
     *
     * @return void
     */
    public function testWithOrdering()
    {
        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $query = $lens->query(Anime::class);

        $query->assertWithOrdering();
    }

    /**
     * The Anime Large Cover Lens shall filter Anime without a Large Cover image.
     *
     * @return void
     */
    public function testQuery()
    {
        Anime::factory()
            ->has(Image::factory()->count($this->faker->randomDigitNotNull))
            ->count($this->faker->randomDigitNotNull)
            ->create();

        $filtered_animes = Anime::whereDoesntHave('images', function (Builder $image_query) {
            $image_query->where('facet', ImageFacet::COVER_LARGE);
        })
        ->get();

        $lens = $this->novaLens(AnimeCoverLargeLens::class);

        $query = $lens->query(Anime::class);

        foreach ($filtered_animes as $filtered_anime) {
            $query->assertContains($filtered_anime);
        }
        $query->assertCount($filtered_animes->count());
    }
}
