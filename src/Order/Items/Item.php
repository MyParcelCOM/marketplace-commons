<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Items;

use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Weight;

class Item
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $quantity,
        public readonly Price $itemPrice,
        public readonly ?string $sku = null,
        public readonly ?string $imageUrl = null,
        public readonly ?Weight $itemWeight = null,
        public readonly ?FeatureCollection $features = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'id'          => $this->id,
            'sku'         => $this->sku,
            'name'        => $this->name,
            'description' => $this->description,
            'image_url'   => $this->imageUrl,
            'quantity'    => $this->quantity,
            'item_price'  => $this->itemPrice->toArray(),
            'item_weight' => $this->itemWeight?->toArray(),
            'features'    => $this->features?->toArray(),
        ], [$this, 'isNotNull']);
    }

    private function isNotNull($value): bool
    {
        return $value !== null;
    }
}
