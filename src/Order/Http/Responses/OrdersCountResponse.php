<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class OrdersCountResponse implements Responsable
{
    public function __construct(
        private readonly int $ordersCount,
    ) {
    }

    public function toResponse($request): SymfonyResponse
    {
        return new JsonResponse([
            'count' => $this->ordersCount,
        ]);
    }
}
