<?php

declare(strict_types=1);

namespace Tests\Shipment\Items;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Shipment\Items\Item;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

class ItemTest extends TestCase
{
    public function test_it_should_return_is_preferential_origin_only_when_all_inputs_are_nulls(): void
    {
        $item = new Item();

        assertSame([
            'is_preferential_origin' => false,
        ], $item->toArray());
    }

    /**
     * @throws \Random\RandomException
     */
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

        assertEquals([
            'sku'                    => $sku,
            'description'            => $description,
            'image_url'              => $imageUrl,
            'item_value'             => [
                'amount'   => $amount,
                'currency' => $currencyCode,
            ],
            'quantity'               => $quantity,
            'hs_code'                => $hsCode,
            'item_weight'            => $itemWeight,
            'origin_country_code'    => $originCountryCode,
            'is_preferential_origin' => false,
        ], $item->toArray());
    }

    public function test_it_limits_description_to_255_characters(): void
    {
        // Create a description longer than 255 characters
        $longDescription = str_repeat('b', 300);

        $item = new Item(
            description: $longDescription,
        );

        // Description should be truncated to 255 characters
        assertEquals(255, mb_strlen($item->description));
        assertEquals(str_repeat('b', 255), $item->description);
    }

    public function test_it_preserves_description_shorter_than_255_characters(): void
    {
        $shortDescription = 'This is a short description for shipment';

        $item = new Item(
            description: $shortDescription,
        );

        assertEquals($shortDescription, $item->description);
    }

    public function test_it_handles_null_description(): void
    {
        $item = new Item(
            description: null,
        );

        assertSame(null, $item->description);
    }
}
