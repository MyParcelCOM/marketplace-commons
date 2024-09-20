<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration;

use MyParcelCom\Integration\Configuration\Properties\JsonSchemaProperty;

interface JsonSchemaTransformable
{
    public function isRequired(): bool;
    public function toJsonSchemaProperty(): JsonSchemaProperty;
}
