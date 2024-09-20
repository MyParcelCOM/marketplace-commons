<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Properties;

use InvalidArgumentException;

class Select extends Property
{
    public function __construct(
        string $name,
        PropertyType $type,
        string $description,
        array $enum,
        bool $isRequired = false,
        string $hint = '',
    ) {
        if (count($enum) < 1) {
            throw new InvalidArgumentException('Select property requires at least one enum value.');
        }

        parent::__construct($name, $type, $description, $isRequired, enum: $enum, hint: $hint);
    }
}
