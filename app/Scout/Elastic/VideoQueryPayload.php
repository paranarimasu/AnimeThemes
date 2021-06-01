<?php declare(strict_types=1);

namespace App\Scout\Elastic;

use App\Models\Video;
use ElasticScoutDriverPlus\Builders\MatchPhraseQueryBuilder;
use ElasticScoutDriverPlus\Builders\MatchQueryBuilder;
use ElasticScoutDriverPlus\Builders\NestedQueryBuilder;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;

/**
 * Class VideoQueryPayload
 * @package App\Scout\Elastic
 */
class VideoQueryPayload extends ElasticQueryPayload
{
    /**
     * Build Elasticsearch query.
     *
     * @return SearchRequestBuilder
     */
    public function buildQuery(): SearchRequestBuilder
    {
        return Video::boolSearch()
            ->should((new MatchPhraseQueryBuilder())
                ->field('filename')
                ->query($this->parser->getSearch())
            )
            ->should((new MatchQueryBuilder())
                ->field('filename')
                ->query($this->parser->getSearch())
                ->operator('AND')
            )
            ->should((new MatchQueryBuilder())
                ->field('filename')
                ->query($this->parser->getSearch())
                ->operator('AND')
                ->lenient(true)
                ->fuzziness('AUTO')
            )
            ->should((new MatchPhraseQueryBuilder())
                ->field('tags')
                ->query($this->parser->getSearch())
            )
            ->should((new MatchQueryBuilder())
                ->field('tags')
                ->query($this->parser->getSearch())
                ->operator('AND')
            )
            ->should((new MatchQueryBuilder())
                ->field('tags')
                ->query($this->parser->getSearch())
                ->operator('AND')
                ->lenient(true)
                ->fuzziness('AUTO')
            )
            ->should((new MatchPhraseQueryBuilder())
                ->field('tags_slug')
                ->query($this->parser->getSearch())
            )
            ->should((new MatchQueryBuilder())
                ->field('tags_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
            )
            ->should((new MatchQueryBuilder())
                ->field('tags_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
                ->lenient(true)
                ->fuzziness('AUTO')
            )
            ->should((new MatchPhraseQueryBuilder())
                ->field('version_slug')
                ->query($this->parser->getSearch())
            )
            ->should((new MatchQueryBuilder())
                ->field('version_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
            )
            ->should((new MatchQueryBuilder())
                ->field('version_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
                ->lenient(true)
                ->fuzziness('AUTO')
            )
            ->should((new MatchPhraseQueryBuilder())
                ->field('anime_slug')
                ->query($this->parser->getSearch())
            )
            ->should((new MatchQueryBuilder())
                ->field('anime_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
            )
            ->should((new MatchQueryBuilder())
                ->field('anime_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
                ->lenient(true)
                ->fuzziness('AUTO')
            )
            ->should((new MatchPhraseQueryBuilder())
                ->field('synonym_slug')
                ->query($this->parser->getSearch())
            )
            ->should((new MatchQueryBuilder())
                ->field('synonym_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
            )
            ->should((new MatchQueryBuilder())
                ->field('synonym_slug')
                ->query($this->parser->getSearch())
                ->operator('AND')
                ->lenient(true)
                ->fuzziness('AUTO')
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.anime')
                        ->query((new MatchPhraseQueryBuilder())
                            ->field('entries.theme.anime.name')
                            ->query($this->parser->getSearch())
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.anime')
                        ->query((new MatchQueryBuilder())
                            ->field('entries.theme.anime.name')
                            ->query($this->parser->getSearch())
                            ->operator('AND')
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.anime')
                        ->query((new MatchQueryBuilder())
                            ->field('entries.theme.anime.name')
                            ->query($this->parser->getSearch())
                            ->operator('AND')
                            ->lenient(true)
                            ->fuzziness('AUTO')
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.anime')
                        ->query((new NestedQueryBuilder())
                            ->path('entries.theme.anime.synonyms')
                            ->query((new MatchPhraseQueryBuilder())
                                ->field('entries.theme.anime.synonyms.text')
                                ->query($this->parser->getSearch())
                            )
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.anime')
                        ->query((new NestedQueryBuilder())
                            ->path('entries.theme.anime.synonyms')
                            ->query((new MatchQueryBuilder())
                                ->field('entries.theme.anime.synonyms.text')
                                ->query($this->parser->getSearch())
                                ->operator('AND')
                            )
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.anime')
                        ->query((new NestedQueryBuilder())
                            ->path('entries.theme.anime.synonyms')
                            ->query((new MatchQueryBuilder())
                                ->field('entries.theme.anime.synonyms.text')
                                ->query($this->parser->getSearch())
                                ->operator('AND')
                                ->lenient(true)
                                ->fuzziness('AUTO')
                            )
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.song')
                        ->query((new MatchPhraseQueryBuilder())
                            ->field('entries.theme.song.title')
                            ->query($this->parser->getSearch())
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.song')
                        ->query((new MatchQueryBuilder())
                            ->field('entries.theme.song.title')
                            ->query($this->parser->getSearch())
                            ->operator('AND')
                        )
                    )
                )
            )
            ->should((new NestedQueryBuilder())
                ->path('entries')
                ->query((new NestedQueryBuilder())
                    ->path('entries.theme')
                    ->query((new NestedQueryBuilder())
                        ->path('entries.theme.song')
                        ->query((new MatchQueryBuilder())
                            ->field('entries.theme.song.title')
                            ->query($this->parser->getSearch())
                            ->operator('AND')
                            ->lenient(true)
                            ->fuzziness('AUTO')
                        )
                    )
                )
            )
            ->minimumShouldMatch(1);
    }
}
