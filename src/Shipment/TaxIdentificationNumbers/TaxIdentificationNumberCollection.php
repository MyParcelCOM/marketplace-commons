<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

use ArrayObject;
use function array_map;

class TaxIdentificationNumberCollection extends ArrayObject
{
    public function __construct(TaxIdentificationNumber ...$number)
    {
        parent::__construct($number);
    }

    public function toArray(): array
    {
        return array_map(static fn(TaxIdentificationNumber $number) => $number->toArray(), (array) $this);
    }
}
