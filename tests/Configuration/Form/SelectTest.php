<?php

declare(strict_types=1);

namespace Tests\Configuration\Form;

use Faker\Factory;
use InvalidArgumentException;
use MyParcelCom\Integration\Configuration\Form\Select;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    public function test_it_converts_a_select_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);

        $select = new Select(
            name: $name,
            type: PropertyType::STRING,
            description: $description,
            enum: [
                1,
                2,
                3,
            ],
        );

        self::assertEquals([
            $name => [
                'type'        => 'string',
                'description' => $description,
                'enum'        => [
                    1,
                    2,
                    3,
                ],
            ],
        ], $select->toJsonSchemaProperty()->toArray());
    }

    public function test_it_throws_an_invalid_argument_exception_without_enum_values(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Select property requires at least one enum value.');

        $faker = Factory::create();

        new Select(
            name: $faker->word,
            type: PropertyType::STRING,
            description: $faker->words(asText: true),
            enum: [],
        );
    }
}
