<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Request;

use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Services\Validator\InputValidator;

class RequestParser implements ParametersInterface
{
    use InputValidator;
    /**
     * @var \ArrayObject
     */
    private $request;

    /**
     * RequestParser constructor.
     */
    public function __construct()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $this->request = new \ArrayObject($this->validate($post) ?? []);
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
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->request->offsetGet($key);
        } else {
            throw new ParameterNotFoundException($key);
        }
    }
}
