<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * When used this middleware will discontinue all requests that
 * contain shipments with channel other than the expected one.
 */
class MatchingChannelOnly
{
    public function handle(Request $request, Closure $next, string $expectedChannel)
    {
        if ($expectedChannel === $this->getShipmentChannel($request)) {
            return $next($request);
        }

        return new JsonResponse(status: 204);
    }

    private function getShipmentChannel(Request $request): string
    {
        $included = $request->get('included');

        $shipment = collect($included)->first(fn ($include) => $include['type'] === 'shipments', []);

        return (string) Arr::get($shipment, 'attributes.channel');
    }
}
