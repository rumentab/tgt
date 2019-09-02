<?php
/**
 * Created by PhpStorm.
 * User: rumen
 * Date: 21.08.19
 * Time: 17:13
 */

namespace App\Core\Container;


abstract class Mapper
{
    protected const APP_MAPPINGS = [
        \App\Core\Router\RouterInterface::class => \App\Core\Router::class,
    ];

    protected const MAPPING_FILE_NAME = 'mapping';

    /**
     * @var \ArrayObject
     */
    protected $mapping;

    /**
     * Mapper constructor.
     */
    public function __construct()
    {
        $this->mapping = new \ArrayObject(static::APP_MAPPINGS);
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function addMapping(string $key, string $value): void
    {
        $this->mapping->offsetSet($key, $value);
    }

    /**
     * Load mapping depending of the type of the config file
     */
    abstract public function loadMappings(): void;

    /**
     * @param string $key
     * @return null|string
     */
    public function getMapping(string $key): ?string
    {
        return $this->mapping->offsetGet($key);
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return $this->mapping->getArrayCopy();
    }
}
