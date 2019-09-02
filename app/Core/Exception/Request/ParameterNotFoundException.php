<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Exception\Request;

use Throwable;

class ParameterNotFoundException extends \Exception
{
    private const MESSAGE = 'Requested parameter %s not found.';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = empty($message) ? null : "'$message'";
        parent::__construct(sprintf(static::MESSAGE, $message), 404, $previous);
    }

    public function __toString()
    {
        return "Error: \"" . $this->getMessage() . "\", Code: " . $this->getCode();
    }
}
