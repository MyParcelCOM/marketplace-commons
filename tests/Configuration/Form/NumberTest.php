<?php

declare(strict_types=1);

namespace Tests\Configuration\Form;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Form\Number;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    public function test_it_converts_a_number_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);

        $number = new Number(
            name: $name,
            description: $description,
        );

        self::assertEquals([
            $name => [
                'type'        => 'number',
                'description' => $description,
            ],
        ], $number->toJsonSchemaProperty()->toArray());
    }
}
