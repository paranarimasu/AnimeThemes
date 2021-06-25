<?php

declare(strict_types=1);

namespace Jobs\Wiki;

use App\Jobs\SendDiscordNotificationJob;
use App\Models\Wiki\Anime;
use App\Models\Wiki\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Class ThemeTest.
 */
class ThemeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * When a theme is created, a SendDiscordNotification job shall be dispatched.
     *
     * @return void
     */
    public function testThemeCreatedSendsDiscordNotification()
    {
        $anime = Anime::factory()->create();

        Config::set('app.allow_discord_notifications', true);
        Bus::fake(SendDiscordNotificationJob::class);

        Theme::factory()->for($anime)->create();

        Bus::assertDispatched(SendDiscordNotificationJob::class);
    }

    /**
     * When a theme is deleted, a SendDiscordNotification job shall be dispatched.
     *
     * @return void
     */
    public function testThemeDeletedSendsDiscordNotification()
    {
        $theme = Theme::factory()
            ->for(Anime::factory())
            ->create();

        Config::set('app.allow_discord_notifications', true);
        Bus::fake(SendDiscordNotificationJob::class);

        $theme->delete();

        Bus::assertDispatched(SendDiscordNotificationJob::class);
    }

    /**
     * When a theme is restored, a SendDiscordNotification job shall be dispatched.
     *
     * @return void
     */
    public function testThemeRestoredSendsDiscordNotification()
    {
        $theme = Theme::factory()
            ->for(Anime::factory())
            ->create();

        Config::set('app.allow_discord_notifications', true);
        Bus::fake(SendDiscordNotificationJob::class);

        $theme->restore();

        Bus::assertDispatched(SendDiscordNotificationJob::class);
    }

    /**
     * When a theme is updated, a SendDiscordNotification job shall be dispatched.
     *
     * @return void
     */
    public function testThemeUpdatedSendsDiscordNotification()
    {
        $theme = Theme::factory()
            ->for(Anime::factory())
            ->create();

        $changes = Theme::factory()
            ->for(Anime::factory())
            ->make();

        Config::set('app.allow_discord_notifications', true);
        Bus::fake(SendDiscordNotificationJob::class);

        $theme->fill($changes->getAttributes());
        $theme->save();

        Bus::assertDispatched(SendDiscordNotificationJob::class);
    }
}