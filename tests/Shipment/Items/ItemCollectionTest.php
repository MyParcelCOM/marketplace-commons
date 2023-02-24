<?php

declare(strict_types=1);

namespace Tests\Shipment\Items;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Shipment\Items\Item;
use MyParcelCom\Integration\Shipment\Items\ItemCollection;
use PHPUnit\Framework\TestCase;
use function random_int;

class ItemCollectionTest extends TestCase
{
    public function test_it_should_convert_items_collection_to_array(): void
    {
        $faker = Factory::create();
        $description = $faker->text(20);
        $quantity = random_int(10, 99);
        $sku = $faker->text(25);
        $imageUrl = $faker->url();
        $hsCode = $faker->text(15);
        $itemWeight = random_int(1000, 5000);
        $originCountryCode = $faker->countryCode();
        $amount = random_int(1000, 5000);
        $currencyCode = $faker->currencyCode();

        $items = new ItemCollection(
            Mockery::mock(Item::class, [
                'toArray' => [
                    'description'         => $description,
                    'quantity'            => $quantity,
                    'sku'                 => $sku,
                    'image_url'           => $imageUrl,
                    'item_value'          => [
                        'amount'   => $amount,
                        'currency' => $currencyCode,
                    ],
                    'hs_code'             => $hsCode,
                    'item_weight'         => $itemWeight,
                    'origin_country_code' => $originCountryCode,
                ],
            ])
        );

        self::assertEquals([
            [
                'description'         => $description,
                'quantity'            => $quantity,
                'sku'                 => $sku,
                'image_url'           => $imageUrl,
                'item_value'          => [
                    'amount'   => $amount,
                    'currency' => $currencyCode,
                ],
                'hs_code'             => $hsCode,
                'item_weight'         => $itemWeight,
                'origin_country_code' => $originCountryCode,
            ],
        ], $items->toArray());
    }

    public function test_it_should_append_item(): void
    {
        $items = new ItemCollection();
        $items->appendItem(Mockery::mock(Item::class, ['toArray' => ['first']]));
        $items->appendItem(Mockery::mock(Item::class, ['toArray' => ['second']]));

        self::assertEquals([
            ['first'],
            ['second'],
        ], $items->toArray());
    }

    public function test_it_should_prepend_item(): void
    {
        $items = new ItemCollection();
        $items->prependItem(Mockery::mock(Item::class, ['toArray' => ['first']]));
        $items->prependItem(Mockery::mock(Item::class, ['toArray' => ['second']]));

        self::assertEquals([
            ['second'],
            ['first'],
        ], $items->toArray());
    }
}
