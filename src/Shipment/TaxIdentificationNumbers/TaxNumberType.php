<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

use MyCLabs\Enum\Enum;

/**
 * @method static self EORI()
 * @method static self VAT()
 * @method static self IOSS()
 */
class TaxNumberType extends Enum
{
    public const EORI = 'eori';
    public const VAT = 'vat';
    public const IOSS = 'ioss';
}
