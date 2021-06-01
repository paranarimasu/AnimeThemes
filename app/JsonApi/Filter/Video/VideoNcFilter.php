<?php declare(strict_types=1);

namespace App\JsonApi\Filter\Video;

use App\JsonApi\Filter\BooleanFilter;
use App\JsonApi\QueryParser;

/**
 * Class VideoNcFilter
 * @package App\JsonApi\Filter\Video
 */
class VideoNcFilter extends BooleanFilter
{
    /**
     * Create a new filter instance.
     *
     * @param QueryParser $parser
     */
    public function __construct(QueryParser $parser)
    {
        parent::__construct($parser, 'nc');
    }
}
