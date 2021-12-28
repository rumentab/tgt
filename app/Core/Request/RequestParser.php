<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core\Request;

use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Services\Validator\InputValidator;
use ArrayObject;

class RequestParser implements ParametersInterface
{
    use InputValidator;
    /**
     * @var ArrayObject
     */
    private ArrayObject $request;

    /**
     * RequestParser constructor.
     */
    public function __construct()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $this->request = new ArrayObject($this->validate($post) ?? []);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return $this->request->offsetExists($key);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ParameterNotFoundException
     */
    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            return $this->request->offsetGet($key);
        } else {
            throw new ParameterNotFoundException($key);
        }
    }
}
