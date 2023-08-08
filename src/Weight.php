<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

use JetBrains\PhpStorm\ArrayShape;

class Weight
{
    public function __construct(
        private int $amount,
        private WeightUnit $unit,
    ) {
    }

    #[ArrayShape(['amount' => 'int', 'unit' => 'string'])]
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'unit'   => $this->unit->value,
        ];
    }
}
