<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\UuidInterface;

#[Immutable]
class ShopId
{
    public function __construct(
        private UuidInterface $uuid,
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
