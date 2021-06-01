<?php

declare(strict_types=1);

namespace Pivots;

use App\Models\Anime;
use App\Models\Entry;
use App\Models\Theme;
use App\Models\Video;
use App\Pivots\VideoEntry;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class VideoEntryTest
 * @package Pivots
 */
class VideoEntryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A VideoEntry shall belong to a Video.
     *
     * @return void
     */
    public function testVideo()
    {
        $videoEntry = VideoEntry::factory()
            ->for(Video::factory())
            ->for(Entry::factory()->for(Theme::factory()->for(Anime::factory())))
            ->create();

        static::assertInstanceOf(BelongsTo::class, $videoEntry->video());
        static::assertInstanceOf(Video::class, $videoEntry->video()->first());
    }

    /**
     * A VideoEntry shall belong to an Entry.
     *
     * @return void
     */
    public function testEntry()
    {
        $videoEntry = VideoEntry::factory()
            ->for(Video::factory())
            ->for(Entry::factory()->for(Theme::factory()->for(Anime::factory())))
            ->create();

        static::assertInstanceOf(BelongsTo::class, $videoEntry->entry());
        static::assertInstanceOf(Entry::class, $videoEntry->entry()->first());
    }
}
