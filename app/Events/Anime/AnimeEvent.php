<?php

namespace App\Events\Anime;

use App\Events\BaseEvent;
use App\Models\Anime;

abstract class AnimeEvent extends BaseEvent
{
    /**
     * The anime that has dispatched this event.
     *
     * @var \App\Models\Anime
     */
    protected $anime;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Anime $anime
     * @return void
     */
    public function __construct(Anime $anime)
    {
        $this->anime = $anime;
    }

    /**
     * Get the anime that has dispatched this event.
     *
     * @return \App\Models\Anime
     */
    public function getAnime()
    {
        return $this->anime;
    }
}