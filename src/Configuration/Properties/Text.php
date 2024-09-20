<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Properties;

class Text extends Property
{
    public function __construct(
        string $name,
        string $description,
        bool $isRequired = false,
        string $hint = '',
    ) {
        parent::__construct($name, PropertyType::STRING, $description, $isRequired, hint: $hint);
    }
}
