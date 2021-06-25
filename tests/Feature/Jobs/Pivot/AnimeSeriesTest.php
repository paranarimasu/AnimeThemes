<?php

declare(strict_types=1);

namespace Jobs\Pivot;

use App\Jobs\SendDiscordNotificationJob;
use App\Models\Wiki\Anime;
use App\Models\Wiki\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Class AnimeSeriesTest.
 */
class AnimeSeriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * When an Anime is attached to a Series or vice versa, a SendDiscordNotification job shall be dispatched.
     *
     * @return void
     */
    public function testAnimeSeriesCreatedSendsDiscordNotification()
    {
        $anime = Anime::factory()->create();
        $series = Series::factory()->create();

        Config::set('app.allow_discord_notifications', true);
        Bus::fake(SendDiscordNotificationJob::class);

        $anime->series()->attach($series);

        Bus::assertDispatched(SendDiscordNotificationJob::class);
    }

    /**
     * When an Anime is detached from a Series or vice versa, a SendDiscordNotification job shall be dispatched.
     *
     * @return void
     */
    public function testAnimeSeriesDeletedSendsDiscordNotification()
    {
        $anime = Anime::factory()->create();
        $series = Series::factory()->create();
        $anime->series()->attach($series);

        Config::set('app.allow_discord_notifications', true);
        Bus::fake(SendDiscordNotificationJob::class);

        $anime->series()->detach($series);

        Bus::assertDispatched(SendDiscordNotificationJob::class);
    }
}