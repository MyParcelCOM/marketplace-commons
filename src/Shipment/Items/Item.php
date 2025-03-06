<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Items;

use MyParcelCom\Integration\Price;

class Item
{
    public function __construct(
        public readonly ?string $description = null,
        public readonly ?int $quantity = null,
        public readonly ?string $sku = null,
        public readonly ?string $imageUrl = null,
        public readonly ?Price $itemValue = null,
        public readonly ?string $hsCode = null,
        public readonly ?int $itemWeight = null,
        public readonly ?string $originCountryCode = null,
        public readonly bool $isPreferentialOrigin = false,
        public readonly ?ItemWeightUnit $itemWeightUnit = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'sku'                    => $this->sku,
            'description'            => $this->description,
            'image_url'              => $this->imageUrl,
            'item_value'             => $this->itemValue?->toArray(),
            'quantity'               => $this->quantity,
            'hs_code'                => $this->hsCode,
            'item_weight'            => $this->itemWeight,
            'item_weight_unit'       => $this->itemWeightUnit?->value,
            'origin_country_code'    => $this->originCountryCode,
            'is_preferential_origin' => $this->isPreferentialOrigin,
        ], $this->isNotNull(...));
    }

    private function isNotNull($value): bool
    {
        return $value !== null;
    }
}
