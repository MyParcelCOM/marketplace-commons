<?php

declare(strict_types=1);

namespace Tests\Configuration\Properties;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Properties\Checkbox;
use PHPUnit\Framework\TestCase;

class CheckboxTest extends TestCase
{
    public function test_it_converts_a_boolean_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);

        $property = new Checkbox(
            name: $name,
            description: $description,
        );

        self::assertEquals([
            $name => [
                'type'        => 'boolean',
                'description' => $description,
            ],
        ], $property->toArray());

        $hint = $faker->words(asText: true);

        $property = new Checkbox(
            name: $name,
            description: $description,
            hint: $hint,
        );

        self::assertEquals([
            $name => [
                'type'        => 'boolean',
                'description' => $description,
                'meta'        => [
                    'hint' => $hint,
                ],
            ],
        ], $property->toArray());
    }
}
