<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

abstract class Data extends \Spatie\LaravelData\Data
{
    public function clone(array $attributes = []): static
    {
        return static::from(array_merge($this->toArray(), $attributes));
    }

    public function toArray(bool $filterNullValues = true): array
    {
        $array = parent::toArray();

        return $filterNullValues
            ? array_filter($array, fn ($value) => $value !== null)
            : $array;
    }
}
