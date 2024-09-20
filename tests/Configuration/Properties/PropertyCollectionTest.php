<?php

declare(strict_types=1);

namespace Tests\Configuration\Properties;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Properties\Property;
use MyParcelCom\Integration\Configuration\Properties\PropertyCollection;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;
use PHPUnit\Framework\TestCase;

class PropertyCollectionTest extends TestCase
{
    public function test_get_required_property_names(): void
    {
        $faker = Factory::create();

        [$nameOne, $nameTwo] = [$faker->word, $faker->word];
        $description = $faker->words(asText: true);

        $propertyOne = new Property(
            name: $nameOne,
            type: PropertyType::STRING,
            description: $description,
        );
        $propertyTwo = new Property(
            name: $nameTwo,
            type: PropertyType::STRING,
            description: $description,
            isRequired: true,
        );

        $properties = new PropertyCollection($propertyOne, $propertyTwo);

        self::assertEquals([$nameTwo], $properties->getRequired());
    }
}
