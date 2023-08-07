<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

enum ContentType: string
{
    case MERCHANDISE = 'merchandise';
    case SAMPLE_MERCHANDISE = 'sample_merchandise';
    case RETURNED_MERCHANDISE = 'returned_merchandise';
    case GIFTS = 'gifts';
    case DOCUMENTS = 'documents';
}
