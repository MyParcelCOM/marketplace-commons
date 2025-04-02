<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use Throwable;

class RequestInputException extends AbstractRequestException
{
    public function __construct(
        protected string $title,
        string $detail,
        int $code = 400,
        ?Throwable $previous = null,
    ) {
        parent::__construct($title, $detail, $code, $previous);
    }
}
