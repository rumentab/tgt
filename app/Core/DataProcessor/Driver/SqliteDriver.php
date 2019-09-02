<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\DataProcessor\Driver;


use App\Core\Configurator\ConfiguratorInterface;
use App\Core\DataProcessor;
use App\Core\Exception\DataProcessor\DataProcessorConfigurationMissingException;
use App\Core\Exception\DataProcessor\DataProcessorNoConnectionException;

class SqliteDriver extends DataProcessor
{
    /**
     * @var \PDO
     */
    protected $dp;

    /**
     * SqliteDriver constructor.
     * @param ConfiguratorInterface $configurator
     * @throws DataProcessorNoConnectionException
     */
    public function __construct(ConfiguratorInterface $configurator)
    {
        parent::__construct($configurator);
    }

    /**
     * Establish a connection to the data source
     * @param ConfiguratorInterface $configurator
     * @return mixed
     * @throws DataProcessorNoConnectionException
     * @throws DataProcessorConfigurationMissingException
     */
    public function connect(ConfiguratorInterface $configurator)
    {
        if ($configurator->has('config', 'sqlite_dsn')) {
            $dsn = $configurator->get('config', 'sqlite_dsn');
        } else {
            throw new DataProcessorConfigurationMissingException();
        }

        try {
            $connection = new \PDO($dsn, null, null, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
            return $connection;
        } catch (\PDOException $ex) {
            throw new DataProcessorNoConnectionException($ex->getMessage());
        }
    }

    /**
     * Drop the connection to the data source (if needed)
     */
    public function disconnect(): void
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * @param string $string
     * @return string
     */
    public function escape(string $string): string
    {
        return $this->dp->quote($string);
    }

    /**
     * @return bool
     */
    public function checkIfInstalled(): bool
    {
        try {
            $this->dp->query("SELECT 1 FROM users");
            return true;
        } catch (\PDOException $ex) {
            return false;
        }
    }
}
