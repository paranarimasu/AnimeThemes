<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Concerns\Http\Api\PerformsResourceCollectionQuery;
use App\Http\Api\Filter\Base\CreatedAtFilter;
use App\Http\Api\Filter\Base\DeletedAtFilter;
use App\Http\Api\Filter\Base\TrashedFilter;
use App\Http\Api\Filter\Base\UpdatedAtFilter;
use App\Http\Api\Filter\ExternalResource\ExternalResourceSiteFilter;
use Illuminate\Http\Request;

/**
 * Class ExternalResourceCollection.
 */
class ExternalResourceCollection extends BaseCollection
{
    use PerformsResourceCollectionQuery;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'resources';

    /**
     * Transform the resource into a JSON array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map(function (ExternalResourceResource $resource) {
            return $resource->parser($this->parser);
        })->all();
    }

    /**
     * The include paths a client is allowed to request.
     *
     * @return string[]
     */
    public static function allowedIncludePaths(): array
    {
        return [
            'anime',
            'artists',
        ];
    }

    /**
     * The sort field names a client is allowed to request.
     *
     * @return string[]
     */
    public static function allowedSortFields(): array
    {
        return [
            'resource_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'site',
            'link',
            'external_id',
        ];
    }

    /**
     * The filters that can be applied by the client for this resource.
     *
     * @return string[]
     */
    public static function filters(): array
    {
        return [
            ExternalResourceSiteFilter::class,
            CreatedAtFilter::class,
            UpdatedAtFilter::class,
            DeletedAtFilter::class,
            TrashedFilter::class,
        ];
    }
}
