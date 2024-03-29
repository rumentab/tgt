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
use function filter_input_array;

class QueryParser implements ParametersInterface
{
    use InputValidator;
    /**
     * @var ArrayObject
     */
    private ArrayObject $query;

    /**
     * QueryParser constructor.
     */
    public function __construct()
    {
        $query = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $this->query = new ArrayObject($this->validate($query ?? []));
    }

    public function __invoke(ArrayObject $params)
    {
        $iterator = $params->getIterator();
        while ($iterator->valid()) {
            $this->query->offsetSet(
                $iterator->key(),
                $this->validate($iterator->current())
            );
            $iterator->next();
        }
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return $this->query->offsetExists($key);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ParameterNotFoundException
     */
    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            return $this->query->offsetGet($key);
        } else {
            throw new ParameterNotFoundException($key);
        }
    }
}
