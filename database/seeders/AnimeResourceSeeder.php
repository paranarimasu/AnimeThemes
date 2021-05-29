<?php

namespace Database\Seeders;

use App\Enums\ResourceSite;
use App\Models\Anime;
use App\Models\ExternalResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AnimeResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (WikiPages::YEAR_MAP as $yearPage => $years) {

            // Try not to upset Reddit
            sleep(rand(5, 15));

            // Get JSON of Year page content
            $yearWikiContents = WikiPages::getPageContents($yearPage);
            if ($yearWikiContents === null) {
                continue;
            }

            // Match headers of Anime and links of Resources
            // Format: "###[{Anime Name}]({Resource Link})"
            preg_match_all('/###\[(.*)\]\((https\:\/\/.*)\)/m', $yearWikiContents, $animeResourceWikiEntries, PREG_SET_ORDER);

            foreach ($animeResourceWikiEntries as $animeResourceWikiEntry) {
                $animeName = html_entity_decode($animeResourceWikiEntry[1]);
                $resourceLink = html_entity_decode($animeResourceWikiEntry[2]);
                preg_match('/([0-9]+)/', $resourceLink, $externalId);

                // Create Resource Model with link and derived site if it doesn't already exist
                $resource = ExternalResource::where('site', ResourceSite::valueOf($resourceLink))->where('link', $resourceLink)->first();
                if ($resource === null) {
                    Log::info("Creating resource '{$resourceLink}'");
                    $resource = ExternalResource::create(
                        [
                            'site' => ResourceSite::valueOf($resourceLink),
                            'link' => $resourceLink,
                            'external_id' => intval($externalId[1]),
                        ]
                    );
                }

                try {
                    // Attach Anime to Resource by Name if we have a definitive match
                    // This is not guaranteed as an Anime Name may be inconsistent between indices
                    $resourceAnime = Anime::where('name', $animeName)
                        ->whereIn('year', $years)
                        ->whereDoesntHave('externalResources', function ($resourceQuery) use ($resource) {
                            $resourceQuery->where('site', $resource->site->value)->where('link', $resource->link);
                        })
                        ->get();
                    if ($resourceAnime->count() === 1) {
                        Log::info("Attaching resource '{$resourceLink}' to anime '{$animeName}'");
                        $resource->anime()->attach($resourceAnime);
                    }
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }
            }
        }
    }
}
