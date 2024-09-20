<?php

declare(strict_types=1);

namespace Tests\Configuration\Properties;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Properties\Number;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    public function test_it_converts_a_number_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);

        $property = new Number(
            name: $name,
            description: $description,
        );

        self::assertEquals([
            $name => [
                'type'        => 'number',
                'description' => $description,
            ],
        ], $property->toArray());

        $hint = $faker->words(asText: true);

        $property = new Number(
            name: $name,
            description: $description,
            hint: $hint,
        );

        self::assertEquals([
            $name => [
                'type'        => 'number',
                'description' => $description,
                'meta'        => [
                    'hint' => $hint,
                ],
            ],
        ], $property->toArray());
    }
}
