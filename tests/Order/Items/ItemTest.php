<?php

declare(strict_types=1);

namespace Tests\Order\Items;

use Faker\Factory;
use MyParcelCom\Integration\Order\Items\Feature;
use MyParcelCom\Integration\Order\Items\FeatureCollection;
use MyParcelCom\Integration\Order\Items\Item;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Weight;
use MyParcelCom\Integration\WeightUnit;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function test_it_converts_an_item_to_array(): void
    {
        $faker = Factory::create();

        $id = $faker->uuid;
        $name = $faker->name;
        $description = $faker->text;
        $sku = $faker->uuid;
        $imageUrl = $faker->imageUrl;
        $quantity = $faker->numberBetween(1, 100);
        $price = $faker->numberBetween(1000, 9001);
        $currency = $faker->currencyCode;
        $weight = $faker->numberBetween(1, 100);
        $weightUnit = $faker->randomElement(WeightUnit::cases());
        $featureKey = $faker->word;
        $featureValue = $faker->word;

        $item = new Item(
            id: $id,
            name: $name,
            description: $description,
            quantity: $quantity,
            itemPrice: new Price(
                amount: $price,
                currency: $currency,
            ),
            sku: $sku,
            imageUrl: $imageUrl,
            itemWeight: new Weight(
                amount: $weight,
                unit: $weightUnit,
            ),
            features: new FeatureCollection(
                new Feature(
                    key: $featureKey,
                    value: $featureValue,
                ),
            ),
        );

        self::assertEquals([
            'id'          => $id,
            'sku'         => $sku,
            'name'        => $name,
            'description' => $description,
            'image_url'   => $imageUrl,
            'quantity'    => $quantity,
            'item_price'  => [
                'amount'   => $price,
                'currency' => $currency,
            ],
            'item_weight' => [
                'amount' => $weight,
                'unit'   => $weightUnit->value,
            ],
            'features'    => [
                [
                    'key'   => $featureKey,
                    'value' => $featureValue,
                ],
            ],
        ], $item->toArray());
    }

    public function test_it_converts_item_to_array_with_minimum_input(): void
    {
        $faker = Factory::create();

        $id = $faker->uuid;
        $name = $faker->name;
        $description = $faker->text;
        $quantity = $faker->numberBetween(1, 100);
        $price = $faker->numberBetween(1000, 9001);
        $currency = $faker->currencyCode;

        $item = new Item(
            id: $id,
            name: $name,
            description: $description,
            quantity: $quantity,
            itemPrice: new Price(
                amount: $price,
                currency: $currency,
            ),
        );

        self::assertEquals([
            'id'          => $id,
            'name'        => $name,
            'description' => $description,
            'quantity'    => $quantity,
            'item_price'  => [
                'amount'   => $price,
                'currency' => $currency,
            ],
        ], $item->toArray());
    }
}
