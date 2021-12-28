<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core\Exception\Configurator;

use Exception;
use Throwable;

/**
 * Class ConfigurationFolderMissingException
 * @package App\Core\Exception\Configurator
 */
class ConfigurationFolderMissingException extends Exception
{
    private const MESSAGE = 'Configurations folder is missing.';

    /**
     * ConfigurationFolderMissingException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = self::MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}