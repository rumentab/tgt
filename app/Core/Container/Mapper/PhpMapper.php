<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Container\Mapper;


use App\Core\Configurator;
use App\Core\Container\Mapper;

class PhpMapper extends Mapper
{

    /**
     * Load mapping depending of the type of the config file
     */
    public function loadMappings(): void
    {
        $folder = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . Configurator::CONFIGURATIONS_FOLDER;
        $folder = new \DirectoryIterator($folder);

        while ($folder->valid()) {
            if ($folder->current()->isFile() &&
                static::MAPPING_FILE_NAME . '.php' === $folder->current()->getFilename()) {
                $mappings = $folder->current()->getPathname();
                break;
            }
            $folder->next();
        }
        $mappings = include $mappings;
        $mappings = new \ArrayObject($mappings);
        $iterator = $mappings->getIterator();
        while ($iterator->valid()) {
            $this->addMapping($iterator->key(), $iterator->current());
            $iterator->next();
        }
    }
}
