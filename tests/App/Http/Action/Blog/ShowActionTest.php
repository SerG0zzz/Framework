<?php

namespace App\Http\Action\Blog;

use App\Http\Middleware\NotFoundHandler;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class ShowActionTest extends TestCase
{
    public function testSuccess()
    {
        $action = new ShowAction();

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 2);

        $response = $action($request, new NotFoundHandler());

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode(['id' => $id, 'title' => 'Post #' . $id]),
            $response->getBody()->getContents()
        );
    }

    public function testNotFound ()
    {
        $action = new ShowAction();

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 10);

        $response = $action($request, new NotFoundHandler());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('Undefined page', $response->getBody()->getContents());
    }
}