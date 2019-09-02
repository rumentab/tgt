<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core;


use App\Core\Configurator\PhpConfigurator;
use App\Core\Factory\Route;
use App\Core\Request;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private const TEST_ROURTES = [
        'test1' => [
            'method' => 'GET',
            'pattern' => 'test/{id:number}',
            'handler' => 'Test\TestController::testMethod1'
        ],
        'test2' => [
            'method' => 'POST',
            'pattern' => 'test/{parameter:text}',
            'handler' => 'Test\TestController::testMethod2'
        ],
        'test3' => [
            'method' => 'PUT',
            'pattern' => 'test/{id:number}/sometext/{sometext:text}',
            'handler' => 'Test\TestController::testMethod3'
        ]
    ];

    public function testInstance()
    {
        $class = new \ReflectionClass(Router::class);

        $this->assertInstanceOf(Router\RouterInterface::class, $class->newInstanceWithoutConstructor());
    }

    public function testInitialization()
    {
        $configurator = new \ReflectionClass(PhpConfigurator::class);
        $configs = $configurator->getProperty('configurations');
        $configs->setAccessible(true);
        $configuratorInstance = $configurator->newInstanceWithoutConstructor();

        $configs->setValue($configuratorInstance, new \ArrayObject([
            'routes' => new \ArrayObject(static::TEST_ROURTES)
        ]));

        $router = new \ReflectionClass(Router::class);
        $routes = $router->getProperty('routes');
        $routes->setAccessible(true);

        $routerInstance = $router->newInstanceArgs([$configuratorInstance]);

        $this->assertInstanceOf(\ArrayObject::class, $routes->getValue($routerInstance));
        $this->assertEquals(3, $routes->getValue($routerInstance)->count());
        $this->assertEquals('test1', $routes->getValue($routerInstance)->getIterator()->key());
        $this->assertIsArray($routes->getValue($routerInstance)->getIterator()->current());

        return $routerInstance;
    }

    /**
     * @depends testInitialization
     * @param Router $routerInstance
     * @throws \ReflectionException
     * @throws \App\Core\Exception\Request\ParameterNotFoundException
     */
    public function testRouter(Router $routerInstance)
    {
        $request = new \ReflectionClass(Request::class);
        $requestType = $request->getProperty('request_method');
        $requestType->setAccessible(true);
        $requestedPath = $request->getProperty('requested_path');
        $requestedPath->setAccessible(true);
        $requestQuery = $request->getProperty('query');
        $requestQuery->setAccessible(true);

        /** @var Request $requestInstance */
        $requestInstance = $request->newInstanceWithoutConstructor();

        $requestQuery->setValue($requestInstance, new Request\QueryParser());
        $requestType->setValue($requestInstance, 'PUT');
        $requestedPath->setValue($requestInstance, 'test/2/sometext/John+Doe');

        /** @var Route $route */
        $route = $routerInstance->findRoute($requestInstance);

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('test3', $route->getName());
    }
}
