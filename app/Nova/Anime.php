<?php

declare(strict_types=1);

namespace App\Nova;

use App\Enums\AnimeSeason;
use BenSampo\Enum\Enum;
use BenSampo\Enum\Rules\EnumValue;
use Devpartners\AuditableLog\AuditableLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;

/**
 * Class Anime
 * @package App\Nova
 */
class Anime extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static string $model = \App\Models\Anime::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The logical group associated with the resource.
     *
     * @return array|string|null
     */
    public static function group(): array|string|null
    {
        return __('nova.wiki');
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return array|string|null
     */
    public static function label(): array|string|null
    {
        return __('nova.anime');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return array|string|null
     */
    public static function singularLabel(): array|string|null
    {
        return __('nova.anime');
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey(): string
    {
        return Str::kebab(class_basename(get_called_class()));
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        return [
            ID::make(__('nova.id'), 'anime_id')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->sortable(),

            new Panel(__('nova.timestamps'), $this->timestamps()),

            Text::make(__('nova.name'), 'name')
                ->sortable()
                ->rules('required', 'max:192')
                ->help(__('nova.anime_name_help')),

            Slug::make(__('nova.slug'), 'slug')
                ->from('name')
                ->separator('_')
                ->sortable()
                ->rules('required', 'max:192', 'alpha_dash')
                ->updateRules('unique:anime,slug,{{resourceId}},anime_id')
                ->help(__('nova.anime_slug_help')),

            Number::make(__('nova.year'), 'year')
                ->sortable()
                ->min(1960)
                ->max(intval(date('Y')) + 1)
                ->rules('required', 'digits:4', 'integer')
                ->help(__('nova.anime_year_help')),

            Select::make(__('nova.season'), 'season')
                ->options(AnimeSeason::asSelectArray())
                ->displayUsing(function (?Enum $enum) {
                    return $enum ? $enum->description : null;
                })
                ->sortable()
                ->rules('required', (new EnumValue(AnimeSeason::class, false))->__toString())
                ->help(__('nova.anime_season_help')),

            Textarea::make(__('nova.synopsis'), 'synopsis')
                ->rules('max:65535')
                ->nullable()
                ->help(__('nova.anime_synopsis_help')),

            HasMany::make(__('nova.synonyms'), 'Synonyms', Synonym::class),

            HasMany::make(__('nova.themes'), 'Themes', Theme::class),

            BelongsToMany::make(__('nova.series'), 'Series', Series::class)
                ->searchable()
                ->fields(function () {
                    return [
                        DateTime::make(__('nova.created_at'), 'created_at')
                            ->readonly()
                            ->hideWhenCreating(),

                        DateTime::make(__('nova.updated_at'), 'updated_at')
                            ->readonly()
                            ->hideWhenCreating(),
                    ];
                }),

            BelongsToMany::make(__('nova.external_resources'), 'ExternalResources', ExternalResource::class)
                ->searchable()
                ->fields(function () {
                    return [
                        Text::make(__('nova.as'), 'as')
                            ->rules('nullable', 'max:192')
                            ->help(__('nova.resource_as_help')),

                        DateTime::make(__('nova.created_at'), 'created_at')
                            ->readonly()
                            ->hideWhenCreating(),

                        DateTime::make(__('nova.updated_at'), 'updated_at')
                            ->readonly()
                            ->hideWhenCreating(),
                    ];
                }),

            BelongsToMany::make(__('nova.images'), 'Images', Image::class)
                ->searchable()
                ->fields(function () {
                    return [
                        DateTime::make(__('nova.created_at'), 'created_at')
                            ->readonly()
                            ->hideWhenCreating(),

                        DateTime::make(__('nova.updated_at'), 'updated_at')
                            ->readonly()
                            ->hideWhenCreating(),
                    ];
                }),

            AuditableLog::make(),
        ];
    }

    /**
     * @return array
     */
    protected function timestamps(): array
    {
        return [
            DateTime::make(__('nova.created_at'), 'created_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->readonly(),

            DateTime::make(__('nova.updated_at'), 'updated_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->readonly(),

            DateTime::make(__('nova.deleted_at'), 'deleted_at')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->readonly(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param Request $request
     * @return array
     */
    public function cards(Request $request): array
    {
        return [
            (new Metrics\NewAnime())->width('1/2'),
            (new Metrics\AnimePerDay())->width('1/2'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [
            new Filters\AnimeSeasonFilter(),
            new Filters\AnimeYearFilter(),
            new Filters\CreatedStartDateFilter(),
            new Filters\CreatedEndDateFilter(),
            new Filters\UpdatedStartDateFilter(),
            new Filters\UpdatedEndDateFilter(),
            new Filters\DeletedStartDateFilter(),
            new Filters\DeletedEndDateFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function lenses(Request $request): array
    {
        return [
            new Lenses\AnimeAniDbResourceLens(),
            new Lenses\AnimeAnilistResourceLens(),
            new Lenses\AnimeCoverLargeLens(),
            new Lenses\AnimeCoverSmallLens(),
            new Lenses\AnimePlanetResourceLens(),
            new Lenses\AnimeAnnResourceLens(),
            new Lenses\AnimeKitsuResourceLens(),
            new Lenses\AnimeMalResourceLens(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param Request $request
     * @return array
     */
    public function actions(Request $request): array
    {
        return [];
    }
}
