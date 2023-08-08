<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

use JetBrains\PhpStorm\ArrayShape;

class Price
{
    public function __construct(
        private readonly int $amount,
        private readonly string $currency,
    ) {
    }

    #[ArrayShape(['amount' => 'int', 'currency' => 'string'])]
    public function toArray(): array
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency,
        ];
    }
}
