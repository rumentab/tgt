<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\Response;


use Throwable;

class JsonResponseBadDataException extends \Exception
{
    private const MESSAGE = "Provided data is expected to be instance of array.";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(static::MESSAGE, $code, $previous);
    }
}
