<?php

namespace App\Http\Action\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class ProfilerMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $start = microtime(true);

        /** $var ResponseInterface $responce */
        $response = $next($request);

        $stop = microtime(true);

        return $response->withHeader('X-Profiler-Time', $stop-$start);
    }
}