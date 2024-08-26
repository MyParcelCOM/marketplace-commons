<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Price
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
    ) {
    }

    /**
     * @return array{amount: int, currency: string}
     */
    public function toArray(): array
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency,
        ];
    }
}
