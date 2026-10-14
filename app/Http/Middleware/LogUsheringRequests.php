<?php

namespace App\Http\Middleware;

use App\Support\UsheringLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Writes one structured entry to the dedicated "ushering" log for every web
 * request, including the resolved user, method, URL, status code and duration.
 */
class LogUsheringRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        if ($this->shouldLog($request)) {
            $status = $response->getStatusCode();
            $duration = round((microtime(true) - $start) * 1000, 2);

            $level = match (true) {
                $status >= 500 => 'error',
                $status >= 400 => 'warning',
                default => 'info',
            };

            UsheringLog::record(
                sprintf('%s %s -> %d', $request->method(), $request->path(), $status),
                [
                    'status' => $status,
                    'duration_ms' => $duration,
                    'context' => array_filter([
                        'route' => optional($request->route())->getName(),
                        'action' => optional($request->route())->getActionName(),
                    ]),
                ],
                $level
            );
        }

        return $response;
    }

    protected function shouldLog(Request $request): bool
    {
        // Avoid a self-referential feedback loop from the log viewer itself.
        $route = optional($request->route())->getName();
        if (in_array($route, ['logs.index'], true)) {
            return false;
        }

        return !$request->is('up', '_ignition/*');
    }
}
