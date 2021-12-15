<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use MyParcelCom\Integration\Shipment\Shipment;
use function array_key_exists;
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

        if (empty($originalControllerResponse)) {
            return new JsonResponse(['data' => []]);
        }

        if (!$this->containsShipments($originalControllerResponse)) {
            return $response;
        }

        $items = $originalControllerResponse['items'];

        return new JsonResponse([
            'data' => array_map(
                static fn(Shipment $shipment) => $shipment->transformToJsonApiArray(),
                $items
            ),
            'meta' => array_filter([
                'total_records' => (int) Arr::get($originalControllerResponse, 'total_records'),
                'total_pages'   => (int) Arr::get($originalControllerResponse, 'total_pages'),
            ]),
        ]);
    }

    private function containsShipments(mixed $originalContent): bool
    {
        return is_array($originalContent)
            && array_key_exists('items', $originalContent)
            && array_reduce(
                $originalContent['items'],
                static fn(bool $prev, $item) => $item instanceof Shipment,
                false
        );
    }
}
