<?php declare(strict_types=1);

namespace App\Nova\Filters;

use App\Enums\ThemeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

/**
 * Class ThemeTypeFilter
 * @package App\Nova\Filters
 */
class ThemeTypeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Get the displayable name of the filter.
     *
     * @return array|string|null
     */
    public function name(): array|string|null
    {
        return __('nova.type');
    }

    /**
     * Apply the filter to the given query.
     *
     * @param Request $request
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('type', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param Request $request
     * @return array
     */
    public function options(Request $request): array
    {
        return array_flip(ThemeType::asSelectArray());
    }
}
