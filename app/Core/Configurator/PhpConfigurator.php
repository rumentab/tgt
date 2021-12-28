<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Configurator;

use App\Core\Configurator;
use ArrayObject;
use function is_array;

class PhpConfigurator extends Configurator
{
    /**
     * Load configuration options from file
     */
    protected function loadConfigurations(): void
    {
        while ($this->config_files->valid()) {
            $file = $this->config_files->current();
            if ($file->isFile() && $file->isReadable()) {
                $key = preg_replace('/(\.\w+)?$/', '', $file->getFilename());
                $value = include $file->getPathname();
                if (is_array($value) && !empty($value)) {
                    $this->configurations->offsetSet($key, new ArrayObject($value));
                }
            }
            $this->config_files->next();
        }
    }

    /**
     * @param string $area
     * @param null|string $key
     * @return bool
     */
    public function has(string $area, ?string $key = null): bool
    {
        return is_null($key) ?
            $this->configurations->offsetExists($area) :
            $this->configurations->offsetExists($area) && $this->configurations->offsetGet($area)->offsetExists($key);
    }

    /**
     * @param string $area
     * @param null|string $key
     * @return mixed
     */
    public function get(string $area, ?string $key = null): mixed
    {
        return is_null($key) ?
            $this->configurations->offsetGet($area) :
            $this->configurations->offsetGet($area)[$key];
    }
}
