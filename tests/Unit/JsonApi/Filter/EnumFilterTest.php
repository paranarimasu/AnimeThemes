<?php

namespace Tests\Unit\JsonApi\Filter;

use App\JsonApi\Filter\EnumFilter;
use App\JsonApi\QueryParser;
use BenSampo\Enum\Enum;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnumFilterTest extends TestCase
{
    use WithFaker;

    /**
     * If values that are not mappable to enum instances are specified for the key, don't apply the filter.
     *
     * @return void
     */
    public function testShouldNotApplyIfNoEnums()
    {
        $filterField = $this->faker->word();

        $enumValues = $this->faker->words($this->faker->randomDigitNotNull);

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $filterField => implode(',', $enumValues),
            ],
        ];

        $parser = new QueryParser($parameters);

        $enum = new class($this->faker->numberBetween(0, 2)) extends Enum
        {
            const ZERO = 0;
            const ONE = 1;
            const TWO = 2;
        };

        $filter = new class($parser, $filterField, get_class($enum)) extends EnumFilter
        {
            // We don't need to do any customization
        };

        $this->assertFalse($filter->shouldApplyFilter($parser->getConditions($filterField)[0]));
    }

    /**
     * If all enum values are specified for the key, don't apply the filter.
     *
     * @return void
     */
    public function testShouldNotApplyIfAllEnums()
    {
        $filterField = $this->faker->word();

        $enum = new class($this->faker->numberBetween(0, 2)) extends Enum
        {
            const ZERO = 0;
            const ONE = 1;
            const TWO = 2;
        };

        $enumClass = get_class($enum);

        $enumValues = $enumClass::getValues();

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $filterField => implode(',', $enumValues),
            ],
        ];

        $parser = new QueryParser($parameters);

        $filter = new class($parser, $filterField, get_class($enum)) extends EnumFilter
        {
            // We don't need to do any customization
        };

        $this->assertFalse($filter->shouldApplyFilter($parser->getConditions($filterField)[0]));
    }

    /**
     * The enum filter shall convert enum keys to enum values.
     *
     * @return void
     */
    public function testEnumKeyConvertedToValue()
    {
        $filterField = $this->faker->word();

        $enum = new class($this->faker->numberBetween(0, 2)) extends Enum
        {
            const ZERO = 0;
            const ONE = 1;
            const TWO = 2;
        };

        $parameters = [
            QueryParser::PARAM_FILTER => [
                $filterField => $enum->key,
            ],
        ];

        $parser = new QueryParser($parameters);

        $filter = new class($parser, $filterField, get_class($enum)) extends EnumFilter
        {
            // We don't need to do any customization
        };

        $filterValues = $filter->getFilterValues($parser->getConditions($filterField)[0]);

        $this->assertEquals($enum->value, $filterValues[0]);
    }
}
