<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Router;

use App\Core\Factory\Route;
use App\Core\Request;
use App\Core\Exception\Configurator\RouteMissingException;

interface RouterInterface
{
    /**
     * @param string $route
     * @return null|Route
     */
    public function getRoute(string $route): ?Route;

    /**
     * @param Request $request
     * @return Route|null
     */
    public function findRoute(Request &$request): ?Route;
}
