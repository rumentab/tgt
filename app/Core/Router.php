<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;


use App\Core\Configurator\ConfiguratorInterface;
use App\Core\Factory\Route;
use App\Core\Router\RouterInterface;

class Router implements RouterInterface
{
    protected const ROUTES_FILE = 'routes';

    protected const ROUTE_PATTERNS = [
        '(\\d+)' => 'number',
        '([^\\/]+)' => 'text'
    ];

    /**
     * @var \ArrayObject
     */
    protected $routes;

    public function __construct(ConfiguratorInterface $configurator)
    {
        $this->routes = $configurator->get('routes');
    }

    /**
     * @param Request $request
     * @return Route|null
     */
    public function findRoute(Request &$request): ?Route
    {
        $route = new Route();
        $routes = $this->routes->getIterator();
        while ($routes->valid()) {
            $_route = $routes->current();
            if ($_route['method'] === $request->getRequestMethod()) {
                list($pattern, $parameters) = $this->prepareRoutePattern($_route['pattern']);
                if (\preg_match("/^$pattern$/", $request->getRequestedPath(), $vars)) {
                    $route->setName($routes->key())
                        ->setPattern($_route['pattern'])
                        ->setParams(new \ArrayObject($parameters));
                    list($controller, $methods) = explode('::', $_route['handler']);
                    $route->setController($controller)
                        ->setMethod($methods);
                    $parameters = \array_combine(\array_keys($parameters), \array_slice($vars, 1));
                    $request(new \ArrayObject($parameters));
                    return $route;
                }
            }
            $routes->next();
        }
        return null;
    }

    /**
     * Converts route's pattern as set in routes file into a regex pattern
     * @param string $pattern
     * @return array
     */
    private function prepareRoutePattern(string $pattern): array
    {
        $pattern = \explode("/", $pattern);

        $params = [];

        \array_walk($pattern, function (&$v) use (&$params) {
            if (\preg_match('/^\{(.+)\:(.+)\}$/', $v, $matches)) {
                $params[$matches[1]] = $matches[2];
                $v = \array_search($matches[2], static::ROUTE_PATTERNS);
            }
        });
        return [
            \implode('\/', $pattern),
            $params
        ];
    }

    /**
     * Get route by name
     * @param string $route_name
     * @return Route|null
     */
    public function getRoute(string $route_name): ?Route
    {
        if ($this->routes->offsetExists($route_name)) {
            $_route = $this->routes->offsetGet($route_name);
            list($controller, $methods) = explode('::', $_route['handler']);
            $route = new Route();
            $route->setName($route_name)
                ->setPattern($_route['pattern'])
                ->setController($controller)
                ->setMethod($methods);
            return $route;
        } else {
            return null;
        }
    }
}
