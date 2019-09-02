<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;


use App\Core\Command\CommandInterface;
use App\Core\Request\ParametersInterface;

abstract class Command implements CommandInterface, ParametersInterface
{
    /**
     * @var \ArrayObject
     */
    protected $params;

    public function __construct(array $parameters = [])
    {
        $this->params = new \ArrayObject();
        $this->setParameters($parameters);
        $this->runCommand();
    }

    public function setParameters(array $params): void
    {
        foreach ($params as $p) {
            switch (true) {
                case preg_match('/--([\S]+)=(.*)/', $p, $v):
                    $this->params->offsetSet($v[1], $v[2]);
                    break;
                case preg_match('/--([\S]+)/', $p, $v):
                    $this->params->offsetSet($v[1], true);
                    break;
                case preg_match('/-([A-z])(.*)/', $p, $v):
                    $v[2] = empty($v[2]) ? true : \trim($v[2]);
                    $this->params->offsetSet($v[1], $v[2]);
                    break;
            }
        }
    }

    public function has(string $key): bool
    {
        return $this->params->offsetExists($key);
    }

    public function get(string $key)
    {
        return $this->params->offsetGet($key);
    }
}
