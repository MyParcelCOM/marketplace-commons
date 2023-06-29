<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

use MyCLabs\Enum\Enum;

/**
 * @method static self MILLIGRAM()
 * @method static self GRAM()
 * @method static self KILOGRAM()
 * @method static self OUNCE()
 * @method static self POUND()
 */
class WeightUnit extends Enum
{
    private const MILLIGRAM = 'mg';
    private const GRAM = 'g';
    private const KILOGRAM = 'kg';
    private const OUNCE = 'oz';
    private const POUND = 'lb';
}
