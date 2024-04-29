<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use Throwable;

class RequestUnauthorizedException extends AbstractRequestException
{
    public function __construct(
        protected string $title,
        string $detail,
        int $code = 401,
        Throwable $previous = null,
    ) {
        parent::__construct($title, $detail, $code, $previous);
    }
}
