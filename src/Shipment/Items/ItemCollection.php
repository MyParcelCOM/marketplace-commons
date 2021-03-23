<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Items;

use ArrayObject;
use function array_map;

class ItemCollection extends ArrayObject
{
    public function __construct(Item ...$items)
    {
        parent::__construct($items);
    }

    public function appendItem(Item $item): void
    {
        $this->append($item);
    }

    public function prependItem(Item $item): void
    {
        $items = (array) $this;
        array_unshift($items, $item);
        $this->exchangeArray($items);
    }

    public function toArray(): array
    {
        return array_map(static fn (Item $item) => $item->toArray(), (array) $this);
    }
}
