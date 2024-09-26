<?php

declare(strict_types=1);

namespace Tests\Configuration\Properties;

use Faker\Factory;
use Illuminate\Support\Arr;
use MyParcelCom\Integration\Configuration\Properties\JsonSchemaProperty;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;
use MyParcelCom\Integration\Order\Items\Annotation;
use MyParcelCom\Integration\Order\Items\Feature;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class JsonSchemaPropertyTest extends TestCase
{
    public function test_it_converts_property_with_minimal_properties_into_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->words(asText: true);
        $type = $faker->randomElement(PropertyType::cases());

        $property = new JsonSchemaProperty(
            name: $name,
            type: $type,
            description: $description,
        );

        self::assertFalse($property->isRequired);
        self::assertEquals([
            $name => [
                'type'        => $type->value,
                'description' => $description,
            ],
        ], $property->toArray());
    }

    public function test_it_converts_property_with_all_properties_into_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word();
        $type = $faker->randomElement(PropertyType::cases());
        $description = $faker->words(asText: true);
        $enum = [
            $faker->word(),
            $faker->word(),
            $faker->word(),
        ];
        $hint = $faker->words(asText: true);

        $property = new JsonSchemaProperty(
            name: $name,
            type: $type,
            description: $description,
            isRequired: true,
            isPassword: true,
            enum: $enum,
            hint: $hint,
        );

        self::assertTrue($property->isRequired);
        self::assertEquals([
            $name => [
                'type'        => $type->value,
                'description' => $description,
                'enum'        => $enum,
                'meta'        => [
                    'hint' => $hint,
                    'password' => true,
                ],
            ],
        ], $property->toArray());
    }
}
