<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyCLabs\Enum\Enum;

/**
 * @method static ContentType MERCHANDISE()
 * @method static ContentType SAMPLE_MERCHANDISE()
 * @method static ContentType RETURNED_MERCHANDISE()
 * @method static ContentType GIFTS()
 * @method static ContentType DOCUMENTS()
 */
class ContentType extends Enum
{
    private const MERCHANDISE = 'merchandise';
    private const SAMPLE_MERCHANDISE = 'sample_merchandise';
    private const RETURNED_MERCHANDISE = 'returned_merchandise';
    private const GIFTS = 'gifts';
    private const DOCUMENTS = 'documents';
}
