<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\Configurator;


use Throwable;

class ConfigurationFolderMissingException extends \Exception
{
    private const MESSAGE = 'Configurations folder is missing.';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = static::MESSAGE;
        parent::__construct($message, $code, $previous);
    }
}