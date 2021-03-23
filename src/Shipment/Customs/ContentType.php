<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyCLabs\Enum\Enum;

/**
 * @method ContentType MERCHANDISE()
 * @method ContentType SAMPLE_MERCHANDISE()
 * @method ContentType RETURNED_MERCHANDISE()
 * @method ContentType GIFTS()
 * @method ContentType DOCUMENTS()
 */
class ContentType extends Enum
{
    private const MERCHANDISE = 'merchandise';
    private const SAMPLE_MERCHANDISE = 'sample_merchandise';
    private const RETURNED_MERCHANDISE = 'returned_merchandise';
    private const GIFTS = 'gifts';
    private const DOCUMENTS = 'documents';
}
