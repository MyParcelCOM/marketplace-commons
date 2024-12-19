<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Items;

enum Annotation: string
{
    case COLOR = 'color';
    case SIZE = 'size';
    case BRAND = 'brand';
}
