<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Form;

use MyParcelCom\Integration\Configuration\Field;
use MyParcelCom\Integration\Configuration\Properties\JsonSchemaProperty;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;

class Checkbox implements Field
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly bool $isRequired = false,
        public readonly ?string $hint = null,
    ) {
    }

    public function toJsonSchemaProperty(): JsonSchemaProperty
    {
        return new JsonSchemaProperty(
            name: $this->name,
            type: PropertyType::BOOLEAN,
            description: $this->description,
            isRequired: $this->isRequired,
            hint: $this->hint,
        );
    }
}
