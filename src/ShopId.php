<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-immutable
 */
class ShopId
{
    public function __construct(
        public readonly UuidInterface $uuid,
    ) {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
