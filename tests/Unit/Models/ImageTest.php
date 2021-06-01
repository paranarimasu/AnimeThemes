<?php declare(strict_types=1);

namespace Models;

use App\Enums\ImageFacet;
use App\Models\Anime;
use App\Models\Artist;
use App\Models\Image;
use App\Pivots\AnimeImage;
use App\Pivots\ArtistImage;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class ImageTest
 * @package Models
 */
class ImageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * The facet attribute of an image shall be cast to an ImageFacet enum instance.
     *
     * @return void
     */
    public function testCastsFacetToEnum()
    {
        $image = Image::factory()->create();

        $facet = $image->facet;

        static::assertInstanceOf(ImageFacet::class, $facet);
    }

    /**
     * Images shall be auditable.
     *
     * @return void
     */
    public function testAuditable()
    {
        Config::set('audit.console', true);

        $image = Image::factory()->create();

        static::assertEquals(1, $image->audits->count());
    }

    /**
     * Images shall be nameable.
     *
     * @return void
     */
    public function testNameable()
    {
        $image = Image::factory()->create();

        static::assertIsString($image->getName());
    }

    /**
     * Image shall have a many-to-many relationship with the type Anime.
     *
     * @return void
     */
    public function testAnime()
    {
        $animeCount = $this->faker->randomDigitNotNull;

        $image = Image::factory()
            ->has(Anime::factory()->count($animeCount))
            ->create();

        static::assertInstanceOf(BelongsToMany::class, $image->anime());
        static::assertEquals($animeCount, $image->anime()->count());
        static::assertInstanceOf(Anime::class, $image->anime()->first());
        static::assertEquals(AnimeImage::class, $image->anime()->getPivotClass());
    }

    /**
     * Image shall have a many-to-many relationship with the type Artist.
     *
     * @return void
     */
    public function testArtists()
    {
        $artistCount = $this->faker->randomDigitNotNull;

        $image = Image::factory()
            ->has(Artist::factory()->count($artistCount))
            ->create();

        static::assertInstanceOf(BelongsToMany::class, $image->artists());
        static::assertEquals($artistCount, $image->artists()->count());
        static::assertInstanceOf(Artist::class, $image->artists()->first());
        static::assertEquals(ArtistImage::class, $image->artists()->getPivotClass());
    }

    /**
     * The image shall not be deleted from storage when the Image is deleted.
     *
     * @return void
     */
    public function testImageStorageDeletion()
    {
        $fs = Storage::fake('images');
        $file = File::fake()->image($this->faker->word().'.jpg');
        $fsFile = $fs->putFile('', $file);
        $fsPathinfo = pathinfo(strval($fsFile));

        $image = Image::create([
            'path' => $fsFile,
            'facet' => ImageFacet::getRandomValue(),
            'size' => $this->faker->randomNumber(),
            'mimetype' => MimeType::fromFilename($fsPathinfo['basename']),
        ]);

        $image->delete();

        static::assertTrue($fs->exists($image->path));
    }

    /**
     * The image shall be deleted from storage when the Image is force deleted.
     *
     * @return void
     */
    public function testImageStorageForceDeletion()
    {
        $fs = Storage::fake('images');
        $file = File::fake()->image($this->faker->word().'.jpg');
        $fsFile = $fs->putFile('', $file);
        $fsPathinfo = pathinfo(strval($fsFile));

        $image = Image::create([
            'path' => $fsFile,
            'facet' => ImageFacet::getRandomValue(),
            'size' => $this->faker->randomNumber(),
            'mimetype' => MimeType::fromFilename($fsPathinfo['basename']),
        ]);

        $image->forceDelete();

        static::assertFalse($fs->exists($image->path));
    }
}
