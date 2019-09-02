<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core;


use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testInstance()
    {
        $request = new Request();

        $this->assertInstanceOf(Request::class, $request);
    }

    public function testQuery()
    {
        $request = new Request();

        $this->assertInstanceOf(Request\QueryParser::class, $request->getQuery());
    }

    public function testRequest()
    {
        $request = new Request();

        $this->assertInstanceOf(Request\RequestParser::class, $request->getRequest());
    }

    public function testParameters()
    {
        $parameters = new \ArrayObject(['test' => 'test123']);

        $request = new Request();

        $request($parameters);

        $this->assertTrue($request->getQuery()->has('test'));

        $this->assertEquals($request->getQuery()->get('test'), 'test123');
    }

    public function testParamNotExists()
    {
        $request = new Request();

        $this->expectException(ParameterNotFoundException::class);

        $this->expectExceptionMessage("Requested parameter 'non_existing_parameter' not found.");

        $request->getRequest()->get('non_existing_parameter');
    }
}
