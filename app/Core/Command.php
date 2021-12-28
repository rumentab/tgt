<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;

use App\Core\Command\CommandInterface;
use App\Core\Request\ParametersInterface;
use ArrayObject;
use function trim;

/**
 * Class Command
 * @package App\Core
 */
abstract class Command implements CommandInterface, ParametersInterface
{
    protected ArrayObject $params;

    /**
     * Command constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->params = new ArrayObject();
        $this->setParameters($parameters);
        $this->runCommand();
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        foreach ($parameters as $p) {
            switch (true) {
                case preg_match('/--([\S]+)=(.*)/', $p, $v):
                    $this->params->offsetSet($v[1], $v[2]);
                    break;
                case preg_match('/--([\S]+)/', $p, $v):
                    $this->params->offsetSet($v[1], true);
                    break;
                case preg_match('/-([A-z])(.*)/', $p, $v):
                    $v[2] = empty($v[2]) ? true : trim($v[2]);
                    $this->params->offsetSet($v[1], $v[2]);
                    break;
            }
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->params->offsetExists($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->params->offsetGet($key);
    }
}
