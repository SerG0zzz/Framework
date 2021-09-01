<?php

namespace App\Http\Action\Blog;

use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    public function testSuccess ()
    {
        $action = new IndexAction();
        $response = $action();

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode([
                ['id' => 2, 'title' => 'The Second Post'],
                ['id' => 1, 'title' => 'The First Post'],
            ]),
            $response->getBody()->getContents()
        );
    }
}