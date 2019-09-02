<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Configurator;


interface ConfiguratorInterface
{
    /**
     * @param string $area
     * @param null|string $key
     * @return bool
     */
    public function has(string $area, ?string $key = null): bool;

    /**
     * @param string $area
     * @param null|string $key
     * @return mixed
     */
    public function get(string $area, ?string $key = null);
}
