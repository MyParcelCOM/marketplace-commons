<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use MyParcelCom\Integration\ProvidesJsonAPI;

class TransformsManyToJsonApi
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

        if (!$this->containsJsonAPIObjects($originalControllerResponse)) {
            return $response;
        }

        return new JsonResponse([
            'data' => array_map(
                static fn (ProvidesJsonAPI $resource) => $resource->transformToJsonApiArray(),
                Arr::get($originalControllerResponse, 'items', []),
            ),
            'meta' => array_filter([
                'total_records' => (int) Arr::get($originalControllerResponse, 'total_records'),
                'total_pages'   => (int) Arr::get($originalControllerResponse, 'total_pages'),
            ]),
        ]);
    }

    private function containsJsonAPIObjects(mixed $originalContent): bool
    {
        return is_array($originalContent)
            && array_key_exists('items', $originalContent)
            && array_reduce(
                $originalContent['items'],
                static fn (bool $carry, mixed $item) => $carry && $item instanceof ProvidesJsonAPI,
                true,
            );
    }
}
