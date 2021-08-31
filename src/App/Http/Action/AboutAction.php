<?php

namespace App\Http\Action;

use Laminas\Diactoros\Response\JsonResponse;

class AboutAction
{
    public function __invoke()
    {
        return new JsonResponse('I am s simple site');
    }
}