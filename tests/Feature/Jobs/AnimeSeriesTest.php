<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendDiscordNotification;
use App\Models\Anime;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AnimeSeriesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
        Bus::fake(SendDiscordNotification::class);

        $anime->series()->attach($series);

        Bus::assertDispatched(SendDiscordNotification::class);
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
        Bus::fake(SendDiscordNotification::class);

        $anime->series()->detach($series);

        Bus::assertDispatched(SendDiscordNotification::class);
    }
}