<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

enum Incoterm: string
{
    case DAP = 'DAP';
    case DPP = 'DDP';
}
