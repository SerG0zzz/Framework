<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Next
{
    private $next;
    private $queue;

    public function __construct(\SplQueue $queue, callable $next)
    {
        $this->next = $next;
        $this->queue = $queue;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->next)($request, $response);
        }

        $middleware = $this->queue->dequeue();

        return $middleware($request, $response, function (ServerRequestInterface $request) use ($response) {
            return $this($request, $response);
        });
    }
}