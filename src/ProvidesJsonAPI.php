<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

interface ProvidesJsonAPI
{
    public function transformToJsonApiArray(): array;
}
