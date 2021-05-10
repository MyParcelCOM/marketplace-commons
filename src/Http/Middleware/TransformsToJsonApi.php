<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use MyParcelCom\Integration\Shipment\Shipment;
use function array_map;
use function array_reduce;
use function is_array;
use function is_object;

class TransformsToJsonApi
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            return $response;
        }

        $originalControllerResponse = $response->getOriginalContent();

        if (!$this->containsShipments($originalControllerResponse)) {
            return $response;
        }

        return new JsonResponse([
            'data' => array_map(
                static fn(Shipment $shipment) => $shipment->transformToJsonApiArray(),
                $originalControllerResponse
            ),
        ]);
    }

    private function containsShipments(mixed $originalContent): bool
    {
        return is_array($originalContent)
            && array_reduce(
                $originalContent,
                static fn(bool $prev, $item) => is_object($item) && $item instanceof Shipment,
                false
            );
    }
}
