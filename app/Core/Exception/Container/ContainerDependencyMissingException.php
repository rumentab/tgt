<?php
/**
 * Created by PhpStorm.
 * User: rumen
 * Date: 21.08.19
 * Time: 14:28
 */

namespace App\Core\Exception\Container;


use \Throwable;

class ContainerDependencyMissingException extends \Exception
{
    private const MESSAGE = "Requested dependency %s was not passed to container.";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(static::MESSAGE, $message), $code, $previous);
    }
}