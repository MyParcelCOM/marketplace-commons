<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment;

use DateTimeInterface;
use MyParcelCom\Integration\Shipment\Customs\Customs;
use MyParcelCom\Integration\Shipment\Exception\InvalidChannelException;
use MyParcelCom\Integration\Shipment\Exception\InvalidTagException;
use MyParcelCom\Integration\Shipment\Items\ItemCollection;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumberCollection;
use MyParcelCom\Integration\ShopId;
use function array_filter;
use function is_string;

class Shipment
{
    public function __construct(
        private ShopId $shopId,
        private Address $recipientAddress,
        private string $description,
        private string $customerReference,
        private string $channel,
        private Price $totalValue,
        private Price $price,
        private PhysicalProperties $physicalProperties,
        private ItemCollection $items,
        private ?DateTimeInterface $createdAt = null,
        private ?Address $senderAddress = null,
        private ?Address $returnAddress = null,
        private ?TaxIdentificationNumberCollection $senderTaxIdentificationNumbers = null,
        private ?TaxIdentificationNumberCollection $recipientTaxIdentificationNumbers = null,
        private ?Customs $customs = null,
        private array $tags = [],
    ) {
        if (empty($this->channel)) {
            throw new InvalidChannelException('Shipment channel cannot be empty');
        }

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
                'created_at'                           => $this->createdAt?->getTimestamp(),
                'recipient_address'                    => $this->recipientAddress->toArray(),
                'sender_address'                       => $this->senderAddress?->toArray(),
                'return_address'                       => $this->returnAddress?->toArray(),
                'description'                          => $this->description,
                'customer_reference'                   => $this->customerReference,
                'channel'                              => $this->channel,
                'total_value'                          => array_filter($this->totalValue->toArray()),
                'price'                                => array_filter($this->price->toArray()),
                'physical_properties'                  => array_filter($this->physicalProperties->toArray()),
                'items'                                => array_filter($this->items->toArray()),
                'tags'                                 => array_filter($this->tags),
                'customs'                              => $this->customs ? array_filter($this->customs->toArray()) : null,
                'sender_tax_identification_numbers'    => $this->senderTaxIdentificationNumbers ? array_filter($this->senderTaxIdentificationNumbers->toArray()) : null,
                'recipient_tax_identification_numbers' => $this->recipientTaxIdentificationNumbers ? array_filter($this->recipientTaxIdentificationNumbers->toArray()) : null,
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
