<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Properties;

class Property
{
    public function __construct(
        private readonly string $name,
        private readonly PropertyType $type,
        private readonly ?string $description = null,
        private readonly array $enum = [],
        private readonly array $meta = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            $this->name => array_filter([
                'type' => $this->type->value,
                'description' => $this->description,
                'enum' => $this->enum,
                'meta' => $this->meta,
            ])
        ];
    }
}
