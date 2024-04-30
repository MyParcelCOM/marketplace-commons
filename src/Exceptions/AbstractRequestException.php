<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class AbstractRequestException extends InvalidArgumentException
{
    /**
     * @psalm-pure
     */
    public function __construct(
        protected string $title,
        string $detail,
        int $code = 0,
        Throwable $previous = null,
    ) {
        parent::__construct($detail, $code, $previous);
    }

    public function render(): Response
    {
        return response()->json([
            'errors' => [
                [
                    'status' => (string) $this->code,
                    'title'  => $this->title,
                    'detail' => $this->message,
                ],
            ],
        ], $this->code);
    }
}
