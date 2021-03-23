<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyCLabs\Enum\Enum;

/**
 * @method NonDelivery RETURN()
 * @method NonDelivery ABANDON()
 */
class NonDelivery extends Enum
{
    private const RETURN = 'return';
    private const ABANDON = 'abandon';
}
