<?php

namespace MyParcelCom\Integration\Order;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class OrdersCountResponse implements Responsable
{
    public function __construct(private readonly int $ordersCount)
    {
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request): SymfonyResponse
    {
        return new JsonResponse([
            'count' => $this->ordersCount,
        ]);
    }
}
