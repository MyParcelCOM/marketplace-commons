<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

use ArrayObject;
use function array_map;

class TaxIdentificationNumbers extends ArrayObject
{
    public function __construct(TaxIdentificationNumber ...$taxIdentificationNumbers)
    {
        parent::__construct($taxIdentificationNumbers);
    }

    public function toArray(): array
    {
        return array_map(static fn(TaxIdentificationNumber $taxIdentificationNumber) => $taxIdentificationNumber->toArray(), (array) $this);
    }
}
