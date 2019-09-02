<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core;


use App\Core\Exception\Response\JsonResponseBadDataException;
use App\Core\Response\HtmlResponse;
use App\Core\Response\JsonResponse;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testJsonResponse()
    {
        $response = new JsonResponse();

        $this->assertJson($response->generate(['test' => 'test123']));

        $this->expectException(JsonResponseBadDataException::class);
        $response->generate('bad data');
    }

    public function testHtmlResponse()
    {
        $response = new HtmlResponse();

        $response->generate('<p>Test</p>');

        $this->assertEquals('<p>Test</p>', $response->getBody());
    }
}
