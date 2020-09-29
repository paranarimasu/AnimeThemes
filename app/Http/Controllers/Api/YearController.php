<?php

namespace App\Http\Controllers\Api;

use App\Enums\Season;
use App\Http\Resources\AnimeCollection;
use App\Models\Anime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class YearController extends BaseController
{
    // constants for query parameters
    protected const NAME_QUERY = 'name';
    protected const YEAR_QUERY = 'year';

    /**
     * The array of eager relations.
     *
     * @var array
     */
    protected const EAGER_RELATIONS = [
        'synonyms',
        'series',
        'themes',
        'themes.entries',
        'themes.entries.videos',
        'themes.song',
        'themes.song.artists',
        'externalResources'
    ];

    /**
     * Display a listing of unique years of anime
     *
     * @OA\Get(
     *     path="/year/",
     *     operationId="getYears",
     *     tags={"Anime"},
     *     summary="Get list of unique years of anime",
     *     description="Returns list of unique years",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent(@OA\Items(type="integer",example=2009)))
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return new JsonResponse(Anime::distinct(static::YEAR_QUERY)->orderBy(static::YEAR_QUERY)->pluck(static::YEAR_QUERY));
    }

    /**
     * Display a listing of anime of year by season.
     *
     * @OA\Get(
     *     path="/year/{year}",
     *     operationId="getAnimeOfYearBySeason",
     *     tags={"Anime"},
     *     summary="Get listing of Anime of year by season",
     *     description="Returns listing of Anime of year by season",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent(@OA\Property(property="summer",type="array", @OA\Items(ref="#/components/schemas/AnimeResource")))
     *     )
     * )
     *
     * @param string $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($year) {
        return new JsonResponse((new AnimeCollection(Anime::where(static::YEAR_QUERY, $year)
            ->with(static::EAGER_RELATIONS)
            ->orderBy(static::NAME_QUERY)
            ->get()
        ))->groupBy(function ($item) {
            return Str::lower(Season::getDescription($item->season));
        }));
    }
}
