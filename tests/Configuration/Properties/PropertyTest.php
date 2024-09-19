<?php

declare(strict_types=1);

namespace Tests\Configuration\Properties;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Properties\Property;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;
use MyParcelCom\Integration\Order\Items\Annotation;
use MyParcelCom\Integration\Order\Items\Feature;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class PropertyTest extends TestCase
{
    public function test_it_converts_property_with_minimal_properties_into_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;

        $property = new Property(
            name: $name,
            type: PropertyType::STRING,
        );

        self::assertEquals([
            $name => [
                'type' => 'string',
            ],
        ], $property->toArray());
    }

    public function test_it_converts_property_with_all_properties_into_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);
        $enum = [
            $faker->word,
            $faker->word,
            $faker->word,
        ];
        $meta = [
            'additionalData' => $faker->words(asText: true)
        ];

        $property = new Property(
            name: $name,
            type: PropertyType::STRING,
            description: $description,
            enum: $enum,
            meta: $meta,
        );

        self::assertEquals([
            $name => [
                'type' => 'string',
                'description' => $description,
                'enum' => $enum,
                'meta' => $meta,
            ],
        ], $property->toArray());
    }
}
