<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Properties;

use ArrayObject;

class PropertyCollection extends ArrayObject
{
    public function __construct(Property ...$properties)
    {
        parent::__construct($properties);
    }

    public function toArray(): array
    {
        return array_map(static fn (Property $property) => $property->toArray(), (array) $this);
    }
}
