<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class PhysicalProperties
{
    public function __construct(
        public readonly ?int $weight = null,
        public readonly ?int $height = null,
        public readonly ?int $width = null,
        public readonly ?int $length = null,
        public readonly ?float $volume = null,
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
