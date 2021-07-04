<?php

declare(strict_types=1);

namespace Tests\Feature\Events\Wiki;

use App\Events\Wiki\Artist\ArtistCreated;
use App\Events\Wiki\Artist\ArtistDeleted;
use App\Events\Wiki\Artist\ArtistRestored;
use App\Events\Wiki\Artist\ArtistUpdated;
use App\Models\Wiki\Artist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Class ArtistTest.
 */
class ArtistTest extends TestCase
{
    use RefreshDatabase;

    /**
     * When an Artist is created, an ArtistCreated event shall be dispatched.
     *
     * @return void
     */
    public function testArtistCreatedEventDispatched()
    {
        Event::fake();

        Artist::factory()->create();

        Event::assertDispatched(ArtistCreated::class);
    }

    /**
     * When an Artist is deleted, an ArtistDeleted event shall be dispatched.
     *
     * @return void
     */
    public function testArtistDeletedEventDispatched()
    {
        Event::fake();

        $artist = Artist::factory()->create();

        $artist->delete();

        Event::assertDispatched(ArtistDeleted::class);
    }

    /**
     * When an Artist is restored, an ArtistRestored event shall be dispatched.
     *
     * @return void
     */
    public function testArtistRestoredEventDispatched()
    {
        Event::fake();

        $artist = Artist::factory()->create();

        $artist->restore();

        Event::assertDispatched(ArtistRestored::class);
    }

    /**
     * When an Artist is updated, an ArtistUpdated event shall be dispatched.
     *
     * @return void
     */
    public function testArtistUpdatedEventDispatched()
    {
        Event::fake();

        $artist = Artist::factory()->create();
        $changes = Artist::factory()->make();

        $artist->fill($changes->getAttributes());
        $artist->save();

        Event::assertDispatched(ArtistUpdated::class);
    }
}
