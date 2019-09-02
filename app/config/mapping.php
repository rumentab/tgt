<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

return [
    \App\Core\Response\ResponseInterface::class => \App\Core\Response\JsonResponse::class,
    \App\Core\Configurator\ConfiguratorInterface::class => \App\Core\Configurator\PhpConfigurator::class,
    \App\Core\DataProcessor\DataProcessorInterface::class => \App\Core\DataProcessor\Driver\SqliteDriver::class
];
