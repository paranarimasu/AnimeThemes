<?php

namespace App\Nova\Lenses;

use App\Enums\AnimeSeason;
use App\Enums\ImageFacet;
use App\Nova\Filters\AnimeSeasonFilter;
use App\Nova\Filters\AnimeYearFilter;
use App\Nova\Filters\RecentlyCreatedFilter;
use App\Nova\Filters\RecentlyUpdatedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class AnimeCoverLargeLens extends Lens
{
    /**
     * Get the displayable name of the lens.
     *
     * @return array|string|null
     */
    public function name()
    {
        return __('nova.anime_image_lens', ['facet' => ImageFacet::getDescription(ImageFacet::COVER_LARGE)]);
    }

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->whereDoesntHave('images', function (Builder $image_query) {
                $image_query->where('facet', ImageFacet::COVER_LARGE);
            })
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('nova.id'), 'anime_id')
                ->sortable(),

            Text::make(__('nova.name'), 'name')
                ->sortable(),

            Text::make(__('nova.slug'), 'slug')
                ->sortable(),

            Number::make(__('nova.year'), 'year')
                ->sortable(),

            Select::make(__('nova.season'), 'season')
                ->options(AnimeSeason::asSelectArray())
                ->displayUsing(function ($enum) {
                    return $enum ? $enum->description : null;
                })
                ->sortable(),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new AnimeSeasonFilter,
            new AnimeYearFilter,
            new RecentlyCreatedFilter,
            new RecentlyUpdatedFilter,
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'anime-cover-large-lens';
    }
}
