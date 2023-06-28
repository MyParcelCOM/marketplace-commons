<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use MyParcelCom\Integration\ProvidesJsonAPI;

class TransformsOneToJsonApi
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

        if (!$originalControllerResponse instanceof ProvidesJsonAPI) {
            return $response;
        }

        return new JsonResponse([
            'data' => $originalControllerResponse->transformToJsonApiArray(),
        ]);
    }
}
