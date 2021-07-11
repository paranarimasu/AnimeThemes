<?php

declare(strict_types=1);

namespace App\Http\Api\Filter\Billing\Transaction;

use App\Http\Api\Filter\FloatFilter;
use App\Http\Api\QueryParser;

/**
 * Class TransactionAmountFilter.
 */
class TransactionAmountFilter extends FloatFilter
{
    /**
     * Create a new filter instance.
     *
     * @param QueryParser $parser
     */
    public function __construct(QueryParser $parser)
    {
        parent::__construct($parser, 'amount');
    }
}
