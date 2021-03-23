<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment;

use DateTimeInterface;
use MyParcelCom\Integration\Shipment\Customs\Customs;
use MyParcelCom\Integration\Shipment\Exception\InvalidTagException;
use MyParcelCom\Integration\Shipment\Items\ItemCollection;
use MyParcelCom\Integration\ShopId;
use function array_filter;
use function is_string;

class Shipment
{
    public function __construct(
        private ShopId $shopId,
        private ?DateTimeInterface $createdAt = null,
        private Address $recipientAddress,
        private ?Address $senderAddress = null,
        private ?Address $returnAddress = null,
        private string $description,
        private string $customerReference,
        private string $channel,
        private Price $totalValue,
        private Price $price,
        private PhysicalProperties $physicalProperties,
        private ItemCollection $items,
        private ?Customs $customs = null,
        private array $tags,
    ) {
        foreach ($this->tags as $tag) {
            if (!is_string($tag)) {
                throw new InvalidTagException('Tags should be strings');
            }
        }
    }

    public function transformToJsonApiArray(): array
    {
        return [
            'type'          => 'shipments',
            'attributes'    => array_filter([
                'created_at'          => $this->createdAt ? $this->createdAt->getTimestamp() : null,
                'recipient_address'   => $this->recipientAddress->toArray(),
                'sender_address'      => $this->senderAddress ? $this->senderAddress->toArray() : null,
                'return_address'      => $this->returnAddress ? $this->returnAddress->toArray() : null,
                'description'         => $this->description,
                'customer_reference'  => $this->customerReference,
                'channel'             => $this->channel,
                'total_value'         => array_filter($this->totalValue->toArray()),
                'price'               => array_filter($this->price->toArray()),
                'physical_properties' => array_filter($this->physicalProperties->toArray()),
                'items'               => array_filter($this->items->toArray()),
                'tags'                => array_filter($this->tags),
                'customs'             => $this->customs ? array_filter($this->customs->toArray()) : null,
            ]),
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
