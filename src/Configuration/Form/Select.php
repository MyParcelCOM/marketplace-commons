<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Form;

use InvalidArgumentException;
use MyParcelCom\Integration\Configuration\Properties\JsonSchemaProperty;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;
use MyParcelCom\Integration\Configuration\Field;

class Select implements Field
{
    public function __construct(
        public readonly string $name,
        public readonly PropertyType $type,
        public readonly string $description,
        public readonly bool $isRequired = false,
        public readonly array $enum = [],
        public readonly ?string $hint = null,
    ) {
        if (count($enum) < 1) {
            throw new InvalidArgumentException('Select property requires at least one enum value.');
        }
    }

    public function toJsonSchemaProperty(): JsonSchemaProperty
    {
        return new JsonSchemaProperty(
            name: $this->name,
            type: $this->type,
            description: $this->description,
            isRequired: $this->isRequired,
            enum: $this->enum,
            hint: $this->hint,
        );
    }
}
