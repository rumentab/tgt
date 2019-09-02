<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\Configurator;


use Throwable;

class RouteMissingException extends \Exception
{
    private const MESSAGE = 'Requested route is missing';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(static::MESSAGE, $code, $previous);
    }
}
