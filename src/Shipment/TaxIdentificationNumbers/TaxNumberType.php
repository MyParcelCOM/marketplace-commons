<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

enum TaxNumberType: string
{
    case EORI = 'eori';
    case VAT = 'vat';
    case IOSS = 'ioss';
}
