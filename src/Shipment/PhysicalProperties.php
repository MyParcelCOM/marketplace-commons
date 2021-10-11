<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment;

use JetBrains\PhpStorm\ArrayShape;

class PhysicalProperties
{
    public function __construct(
        private ?int $weight = null,
        private ?int $height = null,
        private ?int $width = null,
        private ?int $length = null,
        private ?float $volume = null,
    ) {
    }

    #[ArrayShape([
        'weight' => "int",
        'height' => "int|null",
        'width'  => "int|null",
        'length' => "int|null",
        'volume' => "float|null",
    ])]
    public function toArray(): array
    {
        return array_filter([
            'weight' => $this->weight,
            'height' => $this->height,
            'width'  => $this->width,
            'length' => $this->length,
            'volume' => $this->volume,
        ]);
    }
}
