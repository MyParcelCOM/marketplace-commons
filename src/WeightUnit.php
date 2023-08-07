<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

enum WeightUnit: string
{
    case MILLIGRAM = 'mg';
    case GRAM = 'g';
    case KILOGRAM = 'kg';
    case OUNCE = 'oz';
    case POUND = 'lb';
}
