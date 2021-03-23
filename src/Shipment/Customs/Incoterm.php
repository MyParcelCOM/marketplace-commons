<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyCLabs\Enum\Enum;

/**
 * @method Incoterm DAP()
 * @method Incoterm DPP()
 */
class Incoterm extends Enum
{
    private const DAP = 'DAP';
    private const DPP = 'DDP';
}
