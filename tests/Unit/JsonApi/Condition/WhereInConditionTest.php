<?php declare(strict_types=1);

namespace JsonApi\Condition;

use App\Enums\Filter\BinaryLogicalOperator;
use App\Enums\Filter\UnaryLogicalOperator;
use App\JsonApi\Condition\Condition;
use App\JsonApi\QueryParser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class WhereInConditionTest
 * @package JsonApi\Condition
 */
class WhereInConditionTest extends TestCase
{
    use WithFaker;

    /**
     * By default, the Where In Condition shall be scoped globally.
     *
     * @return void
     */
    public function testGlobalScope()
    {
        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $condition = Condition::make($field, $values);

        static::assertEmpty($condition->getScope());
    }

    /**
     * The Where In Condition shall parse scope from the query.
     *
     * @return void
     */
    public function testScope()
    {
        $scope = $this->faker->word();

        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $scope => [
                    $field => $values,
                ],
            ],
        ];

        $parser = QueryParser::make($parameters);

        $condition = collect($parser->getConditions($field))->first();

        static::assertEquals($scope, $condition->getScope());
    }

    /**
     * The Where In Condition shall parse the field from the query.
     *
     * @return void
     */
    public function testField()
    {
        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $field => $values,
            ],
        ];

        $parser = QueryParser::make($parameters);

        $condition = collect($parser->getConditions($field))->first();

        static::assertEquals($field, $condition->getField());
    }

    /**
     * By default, the Where In Condition shall use the AND logical operator.
     *
     * @return void
     */
    public function testDefaultLogicalOperator()
    {
        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $field => $values,
            ],
        ];

        $parser = QueryParser::make($parameters);

        $condition = collect($parser->getConditions($field))->first();

        static::assertEquals(BinaryLogicalOperator::AND(), $condition->getLogicalOperator());
    }

    /**
     * The Where In Condition shall parse the logical operator.
     *
     * @return void
     */
    public function testLogicalOperator()
    {
        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $operator = BinaryLogicalOperator::getRandomInstance();

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $field => [
                    $operator->key => $values,
                ],
            ],
        ];

        $parser = QueryParser::make($parameters);

        $condition = collect($parser->getConditions($field))->first();

        static::assertEquals($operator, $condition->getLogicalOperator());
    }

    /**
     * By default, the Where In Condition shall not use the NOT operator.
     *
     * @return void
     */
    public function testNoNotOperator()
    {
        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $field => $values,
            ],
        ];

        $parser = QueryParser::make($parameters);

        $condition = collect($parser->getConditions($field))->first();

        static::assertFalse($condition->useNot());
    }

    /**
     * The Where In Condition shall parse the NOT operator.
     *
     * @return void
     */
    public function testComparisonOperator()
    {
        $field = $this->faker->word();

        $values = collect($this->faker->words())->join(',');

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $field => [
                    UnaryLogicalOperator::NOT => $values,
                ],
            ],
        ];

        $parser = QueryParser::make($parameters);

        $condition = collect($parser->getConditions($field))->first();

        static::assertTrue($condition->useNot());
    }
}
