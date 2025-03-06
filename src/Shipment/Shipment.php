<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment;

use DateTimeInterface;
use MyParcelCom\Integration\Address;
use MyParcelCom\Integration\PhysicalProperties;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\ProvidesJsonAPI;
use MyParcelCom\Integration\Shipment\Customs\Customs;
use MyParcelCom\Integration\Shipment\Exception\InvalidChannelException;
use MyParcelCom\Integration\Shipment\Exception\InvalidTagException;
use MyParcelCom\Integration\Shipment\Items\ItemCollection;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumberCollection;
use MyParcelCom\Integration\Shop\ShopId;

class Shipment implements ProvidesJsonAPI
{
    public function __construct(
        public readonly ShopId $shopId,
        public readonly Address $recipientAddress,
        public readonly string $description,
        public readonly string $customerReference,
        public readonly string $channel,
        public readonly Price $totalValue,
        public readonly Price $price,
        public readonly PhysicalProperties $physicalProperties,
        public readonly ItemCollection $items,
        public readonly ?DateTimeInterface $createdAt = null,
        public readonly ?Address $senderAddress = null,
        public readonly ?Address $returnAddress = null,
        public readonly ?TaxIdentificationNumberCollection $senderTaxIdentificationNumbers = null,
        public readonly ?TaxIdentificationNumberCollection $recipientTaxIdentificationNumbers = null,
        public readonly ?Customs $customs = null,
        public readonly array $tags = [],
        public readonly ?PickUpLocation $pickUpLocation = null,
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
                'pick_up_location'                     => $this->pickUpLocation?->toArray(),
                'sender_address'                       => $this->senderAddress?->toArray(),
                'return_address'                       => $this->returnAddress?->toArray(),
                'description'                          => $this->description,
                'customer_reference'                   => $this->customerReference,
                'channel'                              => $this->channel,
                'total_value'                          => $this->totalValue->toArray(),
                'price'                                => $this->price->toArray(),
                'physical_properties'                  => $this->physicalProperties->toArray(),
                'items'                                => $this->items->toArray(),
                'tags'                                 => $this->tags,
                'customs'                              => $this->customs?->toArray(),
                'sender_tax_identification_numbers'    => $this->senderTaxIdentificationNumbers?->toArray(),
                'recipient_tax_identification_numbers' => $this->recipientTaxIdentificationNumbers?->toArray(),
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

    public function jsonSerialize(): array
    {
        return $this->transformToJsonApiArray();
    }
}
