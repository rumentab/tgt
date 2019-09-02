<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\Container;


use Throwable;

class ContainerMappingMissingException extends \Exception
{
    private const MESSAGE = "Mapping configuration missing.";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(static::MESSAGE, $code, $previous);
    }
}
