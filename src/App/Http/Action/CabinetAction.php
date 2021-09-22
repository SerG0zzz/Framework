<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    

    public function __invoke(ServerRequestInterface $request)
    {
        //throw new \RuntimeException('Error');

        $username = $request->getAttribute(BasicAuthMiddleware::ATTRIBUTE);

        return new HtmlResponse('I am logged in as ' . $username);
    }
}