<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

use JsonSerializable;

interface ProvidesJsonAPI extends JsonSerializable
{
    public function transformToJsonApiArray(): array;
}
