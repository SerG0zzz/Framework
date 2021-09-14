<?php

namespace Framework\Http\Pipeline;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

final class LazyMiddlewareDecorator implements MiddlewareInterface
{
     private $resolver;
     private $container;
     private $service;

     public function __construct(MiddlewareResolver $resolver, ContainerInterface $container, string $service)
     {
         $this->container = $container;
         $this->resolver = $resolver;
         $this->service = $service;
     }

     public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
     {
         $middleware = $this->resolver->resolve($this->container->get($this->service));
         return $middleware->process($request, $handler);
     }
}