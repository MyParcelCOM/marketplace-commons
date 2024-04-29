<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class PhysicalProperties
{
    public function __construct(
        private readonly ?int $weight = null,
        private readonly ?int $height = null,
        private readonly ?int $width = null,
        private readonly ?int $length = null,
        private readonly ?float $volume = null,
    ) {
    }

    /**
     * @return array{
     *     weight: int,
     *     height: int|null,
     *     width: int|null,
     *     length: int|null,
     *     volume: float|null
     * }
     */
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
