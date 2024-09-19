<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration;

use Illuminate\Support\Arr;
use MyParcelCom\Integration\Configuration\Properties\PropertyCollection;
use MyParcelCom\Integration\ProvidesJsonAPI;

class Configuration implements ProvidesJsonAPI
{
    /**
     * @param PropertyCollection $properties
     */
    public function __construct(private readonly PropertyCollection $properties)
    {
    }

    public function transformToJsonApiArray(): array
    {
        return [
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'additionalProperties' => false,
            'required'             => [], // ???
            'properties'           => Arr::collapse($this->properties->toArray()),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->transformToJsonApiArray();
    }

}
