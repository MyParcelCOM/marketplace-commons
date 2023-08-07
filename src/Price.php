<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Price extends Data
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
    ) {
    }
}
