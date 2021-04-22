<?php

namespace Tests\Unit\Nova\Resources;

use App\Nova\Entry;
use App\Nova\Filters\EntryNsfwFilter;
use App\Nova\Filters\EntrySpoilerFilter;
use App\Nova\Filters\RecentlyCreatedFilter;
use App\Nova\Filters\RecentlyUpdatedFilter;
use JoshGaber\NovaUnit\Resources\NovaResourceTest;
use Tests\TestCase;

class EntryTest extends TestCase
{
    use NovaResourceTest;

    /**
     * The Entry Resource shall contain Entry Fields.
     *
     * @return void
     */
    public function testFields()
    {
        $resource = $this->novaResource(Entry::class);

        $resource->assertHasField(__('nova.id'));
        $resource->assertHasField(__('nova.created_at'));
        $resource->assertHasField(__('nova.updated_at'));
        $resource->assertHasField(__('nova.deleted_at'));
        $resource->assertHasField(__('nova.version'));
        $resource->assertHasField(__('nova.episodes'));
        $resource->assertHasField(__('nova.nsfw'));
        $resource->assertHasField(__('nova.spoiler'));
        $resource->assertHasField(__('nova.notes'));
    }

    /**
     * The Entry Resource shall contain an ID field.
     *
     * @return void
     */
    public function testIdField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.id'));

        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertHiddenWhenUpdating();
        $field->assertNotNullable();
        $field->assertSortable();
    }

    /**
     * The Entry Resource shall contain a Created At field.
     *
     * @return void
     */
    public function testCreatedAtField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.created_at'));

        $field->assertHiddenFromIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
        $field->assertNotSortable();
    }

    /**
     * The Entry Resource shall contain an Updated At field.
     *
     * @return void
     */
    public function testUpdatedAtField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.updated_at'));

        $field->assertHiddenFromIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
        $field->assertNotSortable();
    }

    /**
     * The Entry Resource shall contain a Deleted At field.
     *
     * @return void
     */
    public function testDeletedAtField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.deleted_at'));

        $field->assertHiddenFromIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
        $field->assertNotSortable();
    }

    /**
     * The Entry Resource shall contain a Version field.
     *
     * @return void
     */
    public function testVersionField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.version'));

        $field->assertHasRule('nullable');
        $field->assertHasRule('integer');
        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertShownWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNullable();
        $field->assertSortable();
    }

    /**
     * The Entry Resource shall contain a Episodes field.
     *
     * @return void
     */
    public function testEpisodesField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.episodes'));

        $field->assertHasRule('nullable');
        $field->assertHasRule('max:192');
        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertShownWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNullable();
        $field->assertSortable();
    }

    /**
     * The Entry Resource shall contain a NSFW field.
     *
     * @return void
     */
    public function testNsfwField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.nsfw'));

        $field->assertHasRule('nullable');
        $field->assertHasRule('boolean');
        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertShownWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNullable();
        $field->assertSortable();
    }

    /**
     * The Entry Resource shall contain a Spoiler field.
     *
     * @return void
     */
    public function testSpoilerField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.spoiler'));

        $field->assertHasRule('nullable');
        $field->assertHasRule('boolean');
        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertShownWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNullable();
        $field->assertSortable();
    }

    /**
     * The Entry Resource shall contain a Notes field.
     *
     * @return void
     */
    public function testNotesField()
    {
        $resource = $this->novaResource(Entry::class);

        $field = $resource->field(__('nova.notes'));

        $field->assertHasRule('nullable');
        $field->assertHasRule('max:192');
        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertShownWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNullable();
        $field->assertSortable();
    }

    /**
     * The Entry Resource shall contain Entry Filters.
     *
     * @return void
     */
    public function testFilters()
    {
        $resource = $this->novaResource(Entry::class);

        $resource->assertHasFilter(EntryNsfwFilter::class);
        $resource->assertHasFilter(EntrySpoilerFilter::class);
        $resource->assertHasFilter(RecentlyCreatedFilter::class);
        $resource->assertHasFilter(RecentlyUpdatedFilter::class);
    }

    /**
     * The Entry Resource shall contain no Actions.
     *
     * @return void
     */
    public function testActions()
    {
        $resource = $this->novaResource(Entry::class);

        $resource->assertHasNoActions();
    }
}