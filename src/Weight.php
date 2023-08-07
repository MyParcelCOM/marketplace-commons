<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Weight extends Data
{
    public function __construct(
        public int $amount,
        public WeightUnit $unit,
    ) {
    }
}
