<?php declare(strict_types=1);

namespace App\Scout\Elastic;

use App\JsonApi\QueryParser;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;

/**
 * Class ElasticQueryPayload
 * @package App\Scout\Elastic
 */
abstract class ElasticQueryPayload
{
    /**
     * Filter set specified by the client.
     *
     * @var QueryParser
     */
    protected QueryParser $parser;

    /**
     * Create a new query payload instance.
     *
     * @param QueryParser $parser
     */
    final public function __construct(QueryParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Create a new query payload instance.
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function make(...$parameters): static
    {
        return new static(...$parameters);
    }

    /**
     * Build Elasticsearch query.
     *
     * @return SearchRequestBuilder
     */
    abstract public function buildQuery(): SearchRequestBuilder;
}
