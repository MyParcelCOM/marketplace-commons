<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment;

use MyParcelCom\Integration\Address;

class PickUpLocation
{
    public function __construct(
        public readonly string $code,
        public readonly Address $address,
    ) {
    }

    public function toArray(): array
    {
        return [
            'code'    => $this->code,
            'address' => $this->address->toArray(),
        ];
    }
}
