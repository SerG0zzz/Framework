<?php

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Router;
use Framework\Http\Router\Result;

class RouteMiddleware
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            $result = $this->router->match($request);
            foreach ($result->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            //$middleware = $this->resolver->resolve($result->getHandler());
            return $next($request->withAttribute(Result::class, $result));
        } catch (RequestNotMatchedException $e) {
            return $next($request);
        }
    }
}