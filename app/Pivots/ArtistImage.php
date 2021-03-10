<?php

namespace App\Pivots;

use App\Events\Pivot\ArtistImage\ArtistImageCreated;
use App\Events\Pivot\ArtistImage\ArtistImageDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArtistImage extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artist_image';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ArtistImageCreated::class,
        'deleted' => ArtistImageDeleted::class,
    ];

    /**
     * Gets the artist that owns the artist image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function artist()
    {
        return $this->belongsTo('App\Models\Artist', 'artist_id', 'artist_id');
    }

    /**
     * Gets the image that owns the artist image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo('App\Models\Image', 'image_id', 'image_id');
    }
}