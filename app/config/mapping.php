<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

use App\Core\Configurator\ConfiguratorInterface;
use App\Core\Configurator\PhpConfigurator;
use App\Core\DataProcessor\DataProcessorInterface;
use App\Core\DataProcessor\Driver\SqliteDriver;
use App\Core\Response\JsonResponse;
use App\Core\Response\ResponseInterface;

return [
    ResponseInterface::class => JsonResponse::class,
    ConfiguratorInterface::class => PhpConfigurator::class,
    DataProcessorInterface::class => SqliteDriver::class
];
