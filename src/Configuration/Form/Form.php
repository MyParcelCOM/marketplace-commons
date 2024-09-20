<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Form;

use Illuminate\Support\Arr;
use MyParcelCom\Integration\Collection;
use MyParcelCom\Integration\Configuration\JsonSchemaTransformable;

/**
 * @extends Collection<array-key, JsonSchemaTransformable>
 */
class Form extends Collection
{

    public function toArray(): array
    {
        return Arr::map(
            (array) $this,
            static fn (JsonSchemaTransformable $field) => $field->toJsonSchemaProperty()->toArray(),
        );
    }

    public function getRequired(): array
    {
        $requiredProperties = array_filter(
            (array) $this,
            static fn (JsonSchemaTransformable $field) => $field->isRequired(),
        );

        return array_values(
            Arr::map(
                $requiredProperties,
                static fn (JsonSchemaTransformable $field) => $field->name,
            ),
        );
    }
}
