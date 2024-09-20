<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Properties;

use Illuminate\Support\Arr;
use MyParcelCom\Integration\Collection;

/**
 * @extends Collection<array-key, Property>
 */
class PropertyCollection extends Collection
{
    public function getRequired(): array
    {
        $requiredProperties = array_filter(
            (array) $this,
            static fn (Property $property) => $property->isRequired,
        );

        return array_values(
            Arr::map(
                $requiredProperties,
                static fn (Property $property) => $property->name,
            ),
        );
    }
}
