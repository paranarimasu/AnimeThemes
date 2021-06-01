<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ImageFacet;
use App\Enums\ResourceSite;
use App\Models\Anime;
use App\Models\Image;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Seeder;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class SynopsisCoverSeeder
 * @package Database\Seeders
 */
class SynopsisCoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get anime that have MAL resource but not both cover images
        $animes = $this->getUnseededAnime();

        $fs = Storage::disk('images');

        foreach ($animes as $anime) {
            $anilistResource = $anime->externalResources()->firstWhere('site', ResourceSite::ANILIST);
            if ($anilistResource !== null && $anilistResource->external_id !== null) {
                $animeCoverLarge = $anime->images()->firstWhere('facet', ImageFacet::COVER_LARGE);
                $animeCoverSmall = $anime->images()->firstWhere('facet', ImageFacet::COVER_SMALL);

                // Try not to upset Anilist
                sleep(rand(5, 15));

                // Anilist graphql query
                $query = '
                query ($id: Int) {
                    Media (id: $id, type: ANIME) {
                        description
                        coverImage {
                            extraLarge
                            medium
                        }
                    }
                }
                ';

                // Anilist graphql variables
                $variables = [
                    'id' => $anilistResource->external_id,
                ];

                // Anilist graphql api call
                try {
                    $client = new Client();
                    $response = $client->post('https://graphql.anilist.co', [
                        'json' => [
                            'query' => $query,
                            'variables' => $variables,
                        ],
                    ]);
                    $anilistResourceJson = json_decode($response->getBody()->getContents(), true);
                    $anilistSynopsis = Arr::get($anilistResourceJson, 'data.Media.description');
                    $anilistCoverLarge = Arr::get($anilistResourceJson, 'data.Media.coverImage.extraLarge');
                    $anilistCoverSmall = Arr::get($anilistResourceJson, 'data.Media.coverImage.medium');

                    // Set Anime synopsis
                    if ($anilistSynopsis !== null && $anime->synopsis === null) {
                        Log::info("Setting synopsis for anime '{$anime->name}'");
                        $anime->synopsis = $anilistSynopsis;
                        $anime->save();
                    }

                    // Create large cover image
                    if ($anilistCoverLarge !== null && $animeCoverLarge === null) {
                        $coverImageResponse = $client->get($anilistCoverLarge);
                        $coverImage = $coverImageResponse->getBody()->getContents();
                        $coverFile = File::createWithContent(basename($anilistCoverLarge), $coverImage);
                        $coverLarge = $fs->putFile('', $coverFile);

                        $coverLargeImage = Image::create([
                            'facet' => ImageFacet::COVER_LARGE,
                            'path' => $coverLarge,
                            'size' => $coverImageResponse->getHeader('Content-Length')[0],
                            'mimetype' => $coverImageResponse->getHeader('Content-Type')[0],
                        ]);

                        // Attach large cover to anime
                        Log::info("Attaching image '{$coverLargeImage->path}' to anime '{$anime->name}'");
                        $coverLargeImage->anime()->attach($anime);
                    }

                    // Create small cover image
                    if ($anilistCoverSmall !== null && $animeCoverSmall === null) {
                        $coverImageResponse = $client->get($anilistCoverSmall);
                        $coverImage = $coverImageResponse->getBody()->getContents();
                        $coverFile = File::createWithContent(basename($anilistCoverSmall), $coverImage);
                        $coverSmall = $fs->putFile('', $coverFile);

                        $coverSmallImage = Image::create([
                            'facet' => ImageFacet::COVER_SMALL,
                            'path' => $coverSmall,
                            'size' => $coverImageResponse->getHeader('Content-Length')[0],
                            'mimetype' => $coverImageResponse->getHeader('Content-Type')[0],
                        ]);

                        // Attach large cover to anime
                        Log::info("Attaching image '{$coverSmallImage->path}' to anime '{$anime->name}'");
                        $coverSmallImage->anime()->attach($anime);
                    }
                } catch (ClientException $e) {
                    // We may not have a match for this MAL resource
                    Log::info($e->getMessage());
                } catch (ServerException | GuzzleException $e) {
                    // We may have upset Anilist, try again later
                    Log::info($e->getMessage());

                    return;
                }
            }
        }
    }

    /**
     * Get anime that have MAL resource but not both cover images.
     *
     * @return Collection
     */
    protected function getUnseededAnime(): Collection
    {
        return Anime::query()
            ->whereHas('externalResources', function (BelongsToMany $resourceQuery) {
                $resourceQuery->where('site', ResourceSite::ANILIST);
            })->whereDoesntHave('images', function (BelongsToMany $imageQuery) {
                $imageQuery->whereIn('facet', [ImageFacet::COVER_LARGE, ImageFacet::COVER_SMALL]);
            })
            ->get();
    }
}
