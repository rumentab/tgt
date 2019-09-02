<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\DataProcessor;


use Throwable;

class DataProcessorNoConnectionException extends \Exception
{
    private const MESSAGE = 'Connection to data source cannot be established. Error received: %s';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(static::MESSAGE, $message), $code, $previous);
    }
}
