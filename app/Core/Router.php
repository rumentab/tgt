<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core;

use App\Core\Configurator\ConfiguratorInterface;
use App\Core\Factory\Route;
use App\Core\Router\RouterInterface;
use ArrayObject;
use function array_combine;
use function array_keys;
use function array_search;
use function array_slice;
use function array_walk;
use function explode;
use function implode;
use function preg_match;

class Router implements RouterInterface
{
    protected const ROUTE_PATTERNS = [
        '(\\d+)' => 'number',
        '([^\\/]+)' => 'text'
    ];

    protected ArrayObject $routes;

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
                [$pattern, $parameters] = $this->prepareRoutePattern($_route['pattern']);
                if (preg_match("/^$pattern$/", $request->getRequestedPath(), $vars)) {
                    $route->setName($routes->key())
                        ->setPattern($_route['pattern'])
                        ->setParams(new ArrayObject($parameters));
                    [$controller, $methods] = explode('::', $_route['handler']);
                    $route->setController($controller)
                        ->setMethod($methods);
                    $parameters = array_combine(array_keys($parameters), array_slice($vars, 1));
                    $request(new ArrayObject($parameters));
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
        $pattern = explode("/", $pattern);

        $params = [];

        array_walk($pattern, function (&$v) use (&$params) {
            if (preg_match('/^{(.+):(.+)}$/', $v, $matches)) {
                $params[$matches[1]] = $matches[2];
                $v = array_search($matches[2], static::ROUTE_PATTERNS);
            }
        });
        return [
            implode('\/', $pattern),
            $params
        ];
    }

    /**
     * Get route by name
     * @param string $routeName
     * @return Route|null
     */
    public function getRoute(string $routeName): ?Route
    {
        if ($this->routes->offsetExists($routeName)) {
            $_route = $this->routes->offsetGet($routeName);
            [$controller, $methods] = explode('::', $_route['handler']);
            $route = new Route();
            $route->setName($routeName)
                ->setPattern($_route['pattern'])
                ->setController($controller)
                ->setMethod($methods);
            return $route;
        } else {
            return null;
        }
    }
}
