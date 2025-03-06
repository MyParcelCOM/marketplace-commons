<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Items;

enum ItemWeightUnit: string
{
    case GRAM = 'g';
    case KILOGRAM = 'kg';
    case POUND = 'lb';
    case OUNCE = 'oz';
    case MILLIGRAM = 'mg';
}
