<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Price extends Data
{
    public function __construct(
        public int $amount,
        public string $currency,
    ) {
    }
}
