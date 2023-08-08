<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Items;

use ArrayObject;

class FeatureCollection extends ArrayObject
{
    public function __construct(Feature ...$features)
    {
        parent::__construct($features);
    }

    public function toArray(): array
    {
        return array_map(static fn (Feature $feature) => $feature->toArray(), (array) $this);
    }
}
