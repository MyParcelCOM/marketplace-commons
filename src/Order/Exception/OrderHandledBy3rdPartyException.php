<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Exception;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderHandledBy3rdPartyException extends Exception
{
    protected $code = 409;

    public function __construct($message = null, private readonly string $platform = '')
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 'order_handled_by_3rd_party',
                'message' => $this->getMessage(),
                'meta' => [
                    '3rd_party' => $this->platform
                ]
            ]
        ], $this->code);
    }
}
