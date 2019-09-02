<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Request;

use App\Core\Exception\Request\ParameterNotFoundException;

final class ServerVariablesReader
{
    /**
     * Read the $_SERVER global variable
     * @return \ArrayObject
     */
    public static function getVars(): \ArrayObject
    {
        return new \ArrayObject(filter_input_array(INPUT_SERVER));
    }

    /**
     * @param string $key
     * @return string
     * @throws ParameterNotFoundException
     */
    public static function getVar(string $key): string
    {
        $vars = self::getVars();
        $var = $vars->offsetGet($key);
        if (false !== $var) {
            return $var;
        } else {
            throw new ParameterNotFoundException($key);
        }
    }
}
