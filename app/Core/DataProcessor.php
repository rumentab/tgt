<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;


use App\Core\Configurator\ConfiguratorInterface;
use App\Core\DataProcessor\DataProcessorInterface;

abstract class DataProcessor implements DataProcessorInterface
{
    protected $dp;

    /**
     * DataProcessor constructor.
     * @param ConfiguratorInterface $configurator
     * @throws Exception\DataProcessor\DataProcessorNoConnectionException
     */
    public function __construct(ConfiguratorInterface $configurator)
    {
        $this->dp = $this->connect($configurator);
    }

    /**
     * DataProcessor destructor.
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}
