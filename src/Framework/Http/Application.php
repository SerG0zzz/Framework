<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Laminas\Stratigility\Middleware\PathMiddlewareDecorator;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application implements MiddlewareInterface
{
    private $resolver;
    //private $router;
    private $default;
    private $pipeline;

    public function __construct(MiddlewareResolver $resolver, RequestHandlerInterface $default)
    {
        $this->resolver = $resolver;
        //$this->router = $router;
        $this->pipeline = new MiddlewarePipe();
        $this->default = $default;
    }

    public function pipe($path, $middleware = null): void
    {
        if ($middleware === null) {
            $this->pipeline->pipe($this->resolver->resolve($path));
        } else {
            $this->pipeline->pipe(new PathMiddlewareDecorator($path, $this->resolver->resolve($middleware)));
        }
    }

    /*public function run(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->process($request, $response, $this->default);
    }*/

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->process($request, $this->default);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }
}