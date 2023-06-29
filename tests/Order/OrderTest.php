<?php

declare(strict_types=1);

namespace Tests\Order;

use DateTimeInterface;
use Faker\Factory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Address;
use MyParcelCom\Integration\Order\Items\ItemCollection;
use MyParcelCom\Integration\Order\Order;
use MyParcelCom\Integration\ShopId;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_convert_an_order_to_json_api_array(): void
    {
        $faker = Factory::create();
        $shopUuid = $faker->uuid;
        $shopId = Mockery::mock(ShopId::class, ['toString' => $shopUuid]);
        $createdAt = $faker->dateTime;
        $id = $faker->uuid;
        $address = Mockery::mock(Address::class, [
            'toArray' => [
                'test' => 'test',
            ],
        ]);
        $items = Mockery::mock(ItemCollection::class, [
            'toArray' => [
                [
                    'test' => 'test',
                ],
            ],
        ]);

        $order = new Order(
            shopId: $shopId,
            id: $id,
            createdAt: $createdAt,
            recipientAddress: $address,
            items: $items,
        );

        self::assertEquals([
            'type'          => 'orders',
            'id'            => $id,
            'attributes'    => [
                'created_at'        => $createdAt->format(DateTimeInterface::ATOM),
                'recipient_address' => [
                    'test' => 'test',
                ],
                'items'             => [
                    [
                        'test' => 'test',
                    ],
                ],
            ],
            'relationships' => [
                'shop' => [
                    'data' => [
                        'type' => 'shops',
                        'id'   => $shopUuid,
                    ],
                ],
            ],
        ], $order->transformToJsonApiArray());
    }
}
