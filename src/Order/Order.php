<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order;

use DateTimeInterface;
use MyParcelCom\Integration\Address;
use MyParcelCom\Integration\Order\Items\ItemCollection;
use MyParcelCom\Integration\ProvidesJsonAPI;
use MyParcelCom\Integration\ShopId;

class Order implements ProvidesJsonAPI
{
    public function __construct(
        private ShopId $shopId,
        private string $id,
        private DateTimeInterface $createdAt,
        private Address $recipientAddress,
        private ItemCollection $items,
    ) {
    }

    public function transformToJsonApiArray(): array
    {
        return [
            'type'          => 'orders',
            'attributes'    => [
                'id'                => $this->id,
                'created_at'        => $this->createdAt->format(DateTimeInterface::ATOM),
                'recipient_address' => $this->recipientAddress->toArray(),
                'items'             => $this->items->toArray(),
            ],
            'relationships' => [
                'shop' => [
                    'data' => [
                        'type' => 'shops',
                        'id'   => $this->shopId->toString(),
                    ],
                ],
            ],
        ];
    }
}
