<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

use ArrayObject;
use function array_map;

class TaxIdentificationNumberCollection extends ArrayObject
{
    public function __construct(TaxIdentificationNumber ...$numbers)
    {
        parent::__construct($numbers);
    }

    public function toArray(): array
    {
        return array_map(static fn(TaxIdentificationNumber $number) => $number->toArray(), (array) $this);
    }
}
