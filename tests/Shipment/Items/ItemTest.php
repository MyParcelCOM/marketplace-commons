<?php

declare(strict_types=1);

namespace Tests\Shipment\Items;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Shipment\Items\Item;
use MyParcelCom\Integration\Shipment\Price;
use PHPUnit\Framework\TestCase;
use function random_int;

class ItemTest extends TestCase
{
    public function test_it_should_return_is_preferential_origin_only_when_all_inputs_are_nulls(): void
    {
        $item = new Item();

        self::assertEquals([
            'is_preferential_origin' => false,
        ], $item->toArray());
    }

    public function test_it_should_return_full_item_with_all_inputs(): void
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
        $itemValueMock = Mockery::mock(Price::class, [
            'toArray' => [
                'amount'   => $amount,
                'currency' => $currencyCode,
            ],
        ]);

        $item = new Item(
            description: $description,
            quantity: $quantity,
            sku: $sku,
            imageUrl: $imageUrl,
            itemValue: $itemValueMock,
            hsCode: $hsCode,
            itemWeight: $itemWeight,
            originCountryCode: $originCountryCode,
        );

        self::assertEquals([
            'description'            => $description,
            'quantity'               => $quantity,
            'sku'                    => $sku,
            'image_url'              => $imageUrl,
            'item_value'             => [
                'amount'   => $amount,
                'currency' => $currencyCode,
            ],
            'hs_code'                => $hsCode,
            'item_weight'            => $itemWeight,
            'origin_country_code'    => $originCountryCode,
            'is_preferential_origin' => false,
        ], $item->toArray());
    }
}
