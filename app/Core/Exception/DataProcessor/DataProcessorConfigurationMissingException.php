<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\DataProcessor;


use Throwable;

class DataProcessorConfigurationMissingException extends \Exception
{
    private const MESSAGE = 'Configuration not found.';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(static::MESSAGE, $code, $previous);
    }
}
