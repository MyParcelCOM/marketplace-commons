<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Weight
{
    public function __construct(
        public readonly int $amount,
        public readonly WeightUnit $unit,
    ) {
    }

    /**
     * @return array{amount: int, unit: string}
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'unit'   => $this->unit->value,
        ];
    }
}
