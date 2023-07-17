<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Items;

use MyParcelCom\Integration\WeightUnit;

class Item
{
    public function __construct(
        private string $name,
        private string $description,
        private int $quantity,
        private ?string $sku = null,
        private ?string $imageUrl = null,
        private ?int $weight = null,
        private ?WeightUnit $weightUnit = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'sku'              => $this->sku,
            'name'             => $this->name,
            'description'      => $this->description,
            'image_url'        => $this->imageUrl,
            'quantity'         => $this->quantity,
            'item_weight'      => $this->weight,
            'item_weight_unit' => $this->weightUnit?->getValue(),
        ], [$this, 'isNotNull']);
    }

    private function isNotNull($value): bool
    {
        return $value !== null;
    }
}
