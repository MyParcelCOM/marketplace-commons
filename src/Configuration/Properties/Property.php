<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Properties;

class Property
{
    public function __construct(
        public readonly string $name,
        public readonly PropertyType $type,
        public readonly string $description,
        public readonly bool $isRequired = false,
        public readonly bool $isPassword = false,
        public readonly array $enum = [],
        public readonly string $hint = '',
    ) {
    }

    public function toArray(): array
    {
        return [
            $this->name => array_filter([
                'type' => $this->type->value,
                'description' => $this->description,
                'enum' => $this->enum,
                'meta' => array_filter([
                    'hint' => $this->hint,
                    'password' => $this->isPassword,
                ]),
            ])
        ];
    }
}
