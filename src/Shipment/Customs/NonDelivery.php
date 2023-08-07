<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

enum NonDelivery: string
{
    case RETURN = 'return';
    case ABANDON = 'abandon';
}
