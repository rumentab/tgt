<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;


use App\Core\Configurator\ConfiguratorInterface;
use App\Core\Exception\Configurator\ConfigurationFolderMissingException;

abstract class Configurator implements ConfiguratorInterface
{
    const CONFIGURATIONS_FOLDER = 'config';

    /**
     * @var \DirectoryIterator
     */
    protected $config_files;

    /**
     * @var \ArrayObject
     */
    protected $configurations;

    /**
     * Configurator constructor.
     * @throws ConfigurationFolderMissingException
     */
    public function __construct()
    {
        $this->config_files = $this->scanConfigFolder();

        $this->configurations = new \ArrayObject();

        $this->loadConfigurations();
    }

    /**
     * @return \DirectoryIterator
     * @throws ConfigurationFolderMissingException
     */
    private function scanConfigFolder()
    {
        try {
            return new \DirectoryIterator(dirname(__DIR__) . DIRECTORY_SEPARATOR . static::CONFIGURATIONS_FOLDER);
        } catch (\UnexpectedValueException $ex) {
            throw new ConfigurationFolderMissingException();
        }
    }

    /**
     * Read configurations from storage and store the into the $configurations class parameter
     */
    abstract protected function loadConfigurations(): void;
}
