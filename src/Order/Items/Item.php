<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Items;

use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Weight;

class Item
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $description,
        private readonly int $quantity,
        private readonly Price $itemPrice,
        private readonly ?string $sku = null,
        private readonly ?string $imageUrl = null,
        private readonly ?Weight $itemWeight = null,
        private readonly ?FeatureCollection $features = null,
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
