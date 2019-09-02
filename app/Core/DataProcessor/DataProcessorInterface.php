<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\DataProcessor;


use App\Core\Configurator\ConfiguratorInterface;
use App\Core\Exception\DataProcessor\DataProcessorNoConnectionException;

interface DataProcessorInterface
{
    /**
     * Establish a connection to the data source
     * @param ConfiguratorInterface $configurator
     * @return mixed
     * @throws DataProcessorNoConnectionException
     */
    public function connect(ConfiguratorInterface $configurator);

    /**
     * Drop the connection to the data source (if needed)
     */
    public function disconnect(): void;

    /**
     * @param string $string
     * @return string
     */
    public function escape(string $string): string;

    /**
     * Check if the requested driver is running and has the data source created
     * @return bool
     */
    public function checkIfInstalled(): bool;
}
