<?php

namespace Tests\Unit\Nova\Lenses;

use App\Models\Anime;
use App\Models\Entry;
use App\Models\Theme;
use App\Models\Video;
use App\Nova\Filters\CreatedEndDateFilter;
use App\Nova\Filters\CreatedStartDateFilter;
use App\Nova\Filters\DeletedEndDateFilter;
use App\Nova\Filters\DeletedStartDateFilter;
use App\Nova\Filters\UpdatedEndDateFilter;
use App\Nova\Filters\UpdatedStartDateFilter;
use App\Nova\Filters\VideoTypeFilter;
use App\Nova\Lenses\VideoUnlinkedLens;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JoshGaber\NovaUnit\Lenses\NovaLensTest;
use Tests\TestCase;

class VideoUnlinkedTest extends TestCase
{
    use NovaLensTest, RefreshDatabase, WithFaker;

    /**
     * The Video Source Lens shall contain Video Fields.
     *
     * @return void
     */
    public function testFields()
    {
        $this->withoutEvents();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $lens->assertHasField(__('nova.id'));
        $lens->assertHasField(__('nova.filename'));
        $lens->assertHasField(__('nova.resolution'));
        $lens->assertHasField(__('nova.nc'));
        $lens->assertHasField(__('nova.subbed'));
        $lens->assertHasField(__('nova.lyrics'));
        $lens->assertHasField(__('nova.uncen'));
    }

    /**
     * The Video Source Lens fields shall be sortable.
     *
     * @return void
     */
    public function testSortable()
    {
        $this->withoutEvents();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $lens->field(__('nova.id'))->assertSortable();
        $lens->field(__('nova.filename'))->assertSortable();
        $lens->field(__('nova.resolution'))->assertSortable();
        $lens->field(__('nova.nc'))->assertSortable();
        $lens->field(__('nova.subbed'))->assertSortable();
        $lens->field(__('nova.lyrics'))->assertSortable();
        $lens->field(__('nova.uncen'))->assertSortable();
    }

    /**
     * The Video Source Lens shall contain Video Filters.
     *
     * @return void
     */
    public function testFilters()
    {
        $this->withoutEvents();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $lens->assertHasFilter(VideoTypeFilter::class);
        $lens->assertHasFilter(CreatedStartDateFilter::class);
        $lens->assertHasFilter(CreatedEndDateFilter::class);
        $lens->assertHasFilter(UpdatedStartDateFilter::class);
        $lens->assertHasFilter(UpdatedEndDateFilter::class);
        $lens->assertHasFilter(DeletedStartDateFilter::class);
        $lens->assertHasFilter(DeletedEndDateFilter::class);
    }

    /**
     * The Video Source Lens shall contain no Actions.
     *
     * @return void
     */
    public function testActions()
    {
        $this->withoutEvents();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $lens->assertHasNoActions();
    }

    /**
     * The Video Source Lens shall use the 'withFilters' request.
     *
     * @return void
     */
    public function testWithFilters()
    {
        $this->withoutEvents();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $query = $lens->query(Video::class);

        $query->assertWithFilters();
    }

    /**
     * The Video Source Lens shall use the 'withOrdering' request.
     *
     * @return void
     */
    public function testWithOrdering()
    {
        $this->withoutEvents();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $query = $lens->query(Video::class);

        $query->assertWithOrdering();
    }

    /**
     * The Video Source Lens shall filter Videos without Source.
     *
     * @return void
     */
    public function testQuery()
    {
        Video::factory()
            ->count($this->faker->randomDigitNotNull)
            ->create();

        Video::factory()
            ->count($this->faker->randomDigitNotNull)
            ->has(
                Entry::factory()
                    ->count($this->faker->randomDigitNotNull)
                    ->for(Theme::factory()->for(Anime::factory()))
            )
            ->create();

        $filteredVideos = Video::whereDoesntHave('entries')->get();

        $lens = $this->novaLens(VideoUnlinkedLens::class);

        $query = $lens->query(Video::class);

        foreach ($filteredVideos as $filteredVideo) {
            $query->assertContains($filteredVideo);
        }
        $query->assertCount($filteredVideos->count());
    }
}
