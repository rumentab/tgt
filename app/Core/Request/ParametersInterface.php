<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Request;

use App\Core\Exception\Request\ParameterNotFoundException;

interface ParametersInterface
{
    /**
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @return mixed
     * @throws ParameterNotFoundException
     */
    public function get(string $key);
}

