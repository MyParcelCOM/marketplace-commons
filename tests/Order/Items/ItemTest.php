<?php

declare(strict_types=1);

namespace Tests\Order\Items;

use Faker\Factory;
use MyParcelCom\Integration\Order\Items\Item;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Weight;
use MyParcelCom\Integration\WeightUnit;
use Tests\TestCase;

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
        $itemPrice = $faker->numberBetween(1000, 9001);
        $itemCurrency = $faker->currencyCode;
        $weight = $faker->numberBetween(1, 100);
        $weightUnit = $faker->randomElement(WeightUnit::cases());
        $itemFeature = ['key' => $faker->word, 'value' => $faker->word];

        $item = new Item(
            id: $id,
            name: $name,
            description: $description,
            quantity: $quantity,
            itemPrice: Price::from([
                'amount'   => $itemPrice,
                'currency' => $itemCurrency,
            ]),
            sku: $sku,
            imageUrl: $imageUrl,
            itemWeight: Weight::from([
                'amount' => $weight,
                'unit'   => $weightUnit,
            ]),
            features: [
                $itemFeature,
            ],
        );

        self::assertEquals([
            'id'               => $id,
            'sku'              => $sku,
            'name'             => $name,
            'description'      => $description,
            'image_url'        => $imageUrl,
            'quantity'         => $quantity,
            'item_price'  => [
                'amount'   => $itemPrice,
                'currency' => $itemCurrency,
            ],
            'item_weight' => [
                'amount' => $weight,
                'unit'   => $weightUnit->value,
            ],
            'features'    => [
                $itemFeature,
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
        $itemPrice = $faker->numberBetween(1000, 9001);
        $itemCurrency = $faker->currencyCode;

        $item = new Item(
            id: $id,
            name: $name,
            description: $description,
            quantity: $quantity,
            itemPrice: Price::from([
                'amount'   => $itemPrice,
                'currency' => $itemCurrency,
            ]),
        );

        self::assertEquals([
            'id'          => $id,
            'name'        => $name,
            'description' => $description,
            'quantity'    => $quantity,
            'item_price'  => [
                'amount'   => $itemPrice,
                'currency' => $itemCurrency,
            ],
        ], $item->toArray());
    }
}
