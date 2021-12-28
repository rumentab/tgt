<?php
/**
 * Created by PhpStorm.
 * User: rumen
 * Date: 21.08.19
 * Time: 14:28
 */

declare(strict_types=1);

namespace App\Core\Exception\Container;

use Exception;
use Throwable;

class ContainerDependencyMissingException extends Exception
{
    private const MESSAGE = "Requested dependency %s was not passed to container.";

    /**
     * ContainerDependencyMissingException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(static::MESSAGE, $message), $code, $previous);
    }
}
