<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core\Request;

use App\Core\Exception\Request\ParameterNotFoundException;
use ArrayObject;

final class ServerVariablesReader
{
    /**
     * Read the $_SERVER global variable
     * @return ArrayObject
     */
    public static function getVars(): ArrayObject
    {
        return new ArrayObject(filter_input_array(INPUT_SERVER));
    }

    /**
     * @param string $key
     * @return string
     * @throws ParameterNotFoundException
     */
    public static function getVar(string $key): string
    {
        $var = self::getVars()->offsetGet($key);
        if (false !== $var) {
            return $var;
        }

        throw new ParameterNotFoundException($key);
    }
}
