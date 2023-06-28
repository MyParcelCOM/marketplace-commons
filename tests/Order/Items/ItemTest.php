<?php

declare(strict_types=1);

namespace Tests\Order\Items;

use Faker\Factory;
use MyParcelCom\Integration\Order\Items\Item;
use MyParcelCom\Integration\WeightUnit;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function test_it_converts_an_item_to_array(): void
    {
        $faker = Factory::create();

        $name = $faker->name;
        $description = $faker->text;
        $sku = $faker->uuid;
        $imageUrl = $faker->imageUrl;
        $quantity = $faker->numberBetween(1, 100);
        $weight = $faker->numberBetween(1, 100);
        $weightUnit = $faker->randomElement(WeightUnit::values());

        $item = new Item(
            name: $name,
            description: $description,
            quantity: $quantity,
            sku: $sku,
            imageUrl: $imageUrl,
            weight: $weight,
            weightUnit: $weightUnit,
        );

        self::assertEquals([
            'sku'                    => $sku,
            'name'                   => $name,
            'description'            => $description,
            'image_url'              => $imageUrl,
            'quantity'               => $quantity,
            'item_weight'            => $weight,
            'item_weight_unit'       => $weightUnit->getValue(),
        ], $item->toArray());
    }
}
