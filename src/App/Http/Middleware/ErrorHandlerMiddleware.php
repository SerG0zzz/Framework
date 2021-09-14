<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandlerMiddleware
{
    private $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            if ($this->debug) {
                return new JsonResponse([
                    'error' => 'Server error',
                    'code' => $e->getCode(),
                    'massage' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ], 500);
            }
            return new HtmlResponse('Server error', 500);
        }
    }
}