<?php

declare(strict_types=1);

namespace Events\Wiki;

use App\Events\Wiki\Entry\EntryCreated;
use App\Events\Wiki\Entry\EntryDeleted;
use App\Events\Wiki\Entry\EntryRestored;
use App\Events\Wiki\Entry\EntryUpdated;
use App\Models\Wiki\Anime;
use App\Models\Wiki\Entry;
use App\Models\Wiki\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Class EntryTest.
 */
class EntryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * When an Entry is created, an EntryCreated event shall be dispatched.
     *
     * @return void
     */
    public function testEntryCreatedEventDispatched()
    {
        Event::fake(EntryCreated::class);

        Entry::factory()
            ->for(Theme::factory()->for(Anime::factory()))
            ->create();

        Event::assertDispatched(EntryCreated::class);
    }

    /**
     * When an Entry is deleted, an EntryDeleted event shall be dispatched.
     *
     * @return void
     */
    public function testEntryDeletedEventDispatched()
    {
        Event::fake(EntryDeleted::class);

        $entry = Entry::factory()
            ->for(Theme::factory()->for(Anime::factory()))
            ->create();

        $entry->delete();

        Event::assertDispatched(EntryDeleted::class);
    }

    /**
     * When an Entry is restored, an EntryRestored event shall be dispatched.
     *
     * @return void
     */
    public function testEntryRestoredEventDispatched()
    {
        Event::fake(EntryRestored::class);

        $entry = Entry::factory()
            ->for(Theme::factory()->for(Anime::factory()))
            ->create();

        $entry->restore();

        Event::assertDispatched(EntryRestored::class);
    }

    /**
     * When an Entry is updated, an EntryUpdated event shall be dispatched.
     *
     * @return void
     */
    public function testEntryUpdatedEventDispatched()
    {
        Event::fake(EntryUpdated::class);

        $entry = Entry::factory()
            ->for(Theme::factory()->for(Anime::factory()))
            ->create();

        $changes = Entry::factory()
            ->for(Theme::factory()->for(Anime::factory()))
            ->make();

        $entry->fill($changes->getAttributes());
        $entry->save();

        Event::assertDispatched(EntryUpdated::class);
    }
}