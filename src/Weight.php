<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Weight extends Data
{
    public function __construct(
        public readonly int $amount,
        public readonly WeightUnit $unit,
    ) {
    }
}
