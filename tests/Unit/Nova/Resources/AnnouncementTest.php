<?php

namespace Tests\Unit\Nova\Resources;

use App\Nova\Announcement;
use App\Nova\Filters\CreatedEndDateFilter;
use App\Nova\Filters\CreatedStartDateFilter;
use App\Nova\Filters\DeletedEndDateFilter;
use App\Nova\Filters\DeletedStartDateFilter;
use App\Nova\Filters\UpdatedEndDateFilter;
use App\Nova\Filters\UpdatedStartDateFilter;
use Illuminate\Foundation\Testing\WithoutEvents;
use JoshGaber\NovaUnit\Resources\NovaResourceTest;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use NovaResourceTest, WithoutEvents;

    /**
     * The Announcement Resource shall contain Announcement Fields.
     *
     * @return void
     */
    public function testFields()
    {
        $resource = $this->novaResource(Announcement::class);

        $resource->assertHasField(__('nova.id'));
        $resource->assertHasField(__('nova.created_at'));
        $resource->assertHasField(__('nova.updated_at'));
        $resource->assertHasField(__('nova.deleted_at'));
        $resource->assertHasField(__('nova.content'));
    }

    /**
     * The Announcement Resource shall contain an ID field.
     *
     * @return void
     */
    public function testIdField()
    {
        $resource = $this->novaResource(Announcement::class);

        $field = $resource->field(__('nova.id'));

        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertHiddenWhenUpdating();
        $field->assertNotNullable();
        $field->assertSortable();
    }

    /**
     * The Announcement Resource shall contain a Created At field.
     *
     * @return void
     */
    public function testCreatedAtField()
    {
        $resource = $this->novaResource(Announcement::class);

        $field = $resource->field(__('nova.created_at'));

        $field->assertHiddenFromIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
        $field->assertNotSortable();
    }

    /**
     * The Announcement Resource shall contain a Deleted At field.
     *
     * @return void
     */
    public function testUpdatedAtField()
    {
        $resource = $this->novaResource(Announcement::class);

        $field = $resource->field(__('nova.updated_at'));

        $field->assertHiddenFromIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
        $field->assertNotSortable();
    }

    /**
     * The Announcement Resource shall contain an Updated At field.
     *
     * @return void
     */
    public function testDeletedAtField()
    {
        $resource = $this->novaResource(Announcement::class);

        $field = $resource->field(__('nova.deleted_at'));

        $field->assertHiddenFromIndex();
        $field->assertShownOnDetail();
        $field->assertHiddenWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
        $field->assertNotSortable();
    }

    /**
     * The Announcement Resource shall contain a Synopsis field.
     *
     * @return void
     */
    public function testContentField()
    {
        $resource = $this->novaResource(Announcement::class);

        $field = $resource->field(__('nova.content'));

        $field->assertHasRule('required');
        $field->assertHasRule('max:65535');
        $field->assertShownOnIndex();
        $field->assertShownOnDetail();
        $field->assertShownWhenCreating();
        $field->assertShownWhenUpdating();
        $field->assertNotNullable();
    }

    /**
     * The Announcement Resource shall contain Announcement Filters.
     *
     * @return void
     */
    public function testFilters()
    {
        $resource = $this->novaResource(Announcement::class);

        $resource->assertHasFilter(CreatedStartDateFilter::class);
        $resource->assertHasFilter(CreatedEndDateFilter::class);
        $resource->assertHasFilter(UpdatedStartDateFilter::class);
        $resource->assertHasFilter(UpdatedEndDateFilter::class);
        $resource->assertHasFilter(DeletedStartDateFilter::class);
        $resource->assertHasFilter(DeletedEndDateFilter::class);
    }

    /**
     * The Announcement Resource shall contain no Actions.
     *
     * @return void
     */
    public function testActions()
    {
        $resource = $this->novaResource(Announcement::class);

        $resource->assertHasNoActions();
    }
}
