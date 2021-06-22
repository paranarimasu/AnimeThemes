<?php

declare(strict_types=1);

namespace App\Http\Api\Filter\Wiki\Entry;

use App\Http\Api\Filter\IntFilter;
use App\Http\Api\QueryParser;

/**
 * Class EntryVersionFilter.
 */
class EntryVersionFilter extends IntFilter
{
    /**
     * Create a new filter instance.
     *
     * @param QueryParser $parser
     */
    public function __construct(QueryParser $parser)
    {
        parent::__construct($parser, 'version');
    }
}
