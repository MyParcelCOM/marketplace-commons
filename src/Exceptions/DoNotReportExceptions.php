<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use InvalidArgumentException;

class DoNotReportExceptions
{
    private const DEFAULT_DO_NOT_REPORT_LIST = [
        InvalidArgumentException::class,
    ];

    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->dontReport(self::DEFAULT_DO_NOT_REPORT_LIST);
    }
}
