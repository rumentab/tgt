<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core\Exception\Container;

use Exception;
use Throwable;

class ContainerMappingUnsupportedTypeException extends Exception
{
    private const MESSAGE = "Unsupported type for mapping.";

    /**
     * ContainerMappingUnsupportedTypeException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = self::MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
