<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    const ATTRIBUTE = '_user';

    public function __invoke(ServerRequestInterface $request)
    {
        //throw new \RuntimeException('Error');

        $username = $request->getAttribute(self::ATTRIBUTE);

        return new HtmlResponse('I am logged in as ' . $username);
    }
}