<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Items;

use MyParcelCom\Integration\Shipment\Price;
use function array_filter;

class Item
{
    public function __construct(
        private ?string $description,
        private ?int $quantity,
        private ?string $sku = null,
        private ?string $imageUrl = null,
        private ?Price $itemValue = null,
        private ?string $hsCode = null,
        private ?int $itemWeight = null,
        private ?string $originCountryCode = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'sku'                 => $this->sku,
            'description'         => $this->description,
            'image_url'           => $this->imageUrl,
            'item_value'          => $this->itemValue ? array_filter($this->itemValue->toArray()) : null,
            'quantity'            => $this->quantity,
            'hs_code'             => $this->hsCode,
            'item_weight'         => $this->itemWeight,
            'origin_country_code' => $this->originCountryCode,
        ]);
    }
}
