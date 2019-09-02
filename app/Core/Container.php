<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;


use App\Core\Configurator\ConfiguratorInterface;
use App\Core\Container\Mapper;
use App\Core\Exception\Container\ContainerDependencyMissingException;
use App\Core\Factory\Route;
use App\Core\Response\HtmlResponse;
use App\Core\Router\RouterInterface;

class Container
{
    /**
     * @var array
     */
    private static $stack = [];

    private $mapper;

    /**
     * @var Request
     */
    private $request;

    /**
     * Container constructor.
     * @param Request $request
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->registerSingleton($request);
    }

    /**
     * @return Container
     * @throws ContainerDependencyMissingException
     */
    private function runMapper(): self
    {
        /** @var Mapper $mapper */
        $mapper = $this->getInstance('mapper');
        $mapper->loadMappings();
        $this->mapper = $mapper->getMappings();
        return $this;
    }

    /**
     * Register a DI and instantiate class
     * @param string $class
     * @return Container
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function register(string $class): self
    {
        $class = new \ReflectionClass($class);
        $class = $this->getMapped($class);
        if (false === \array_search(md5($class->getName()), self::$stack)) {
            $args = $class->getConstructor() ? $class->getConstructor()->getParameters() : [];
            /** @var \ReflectionParameter $arg */
            foreach ($args as &$arg) {
                $argClass = $this->getMapped($arg->getClass());
                if (!isset(self::$stack[md5($argClass->getName())])) {
                    $this->register($argClass->getName());
                }
                $arg = self::$stack[md5($argClass->getName())];
            }
            self::$stack[md5($class->getName())] = $class->newInstanceArgs($args);
        }
        return $this;
    }

    /**
     * Register already instantiated class as a DI
     * @param mixed $class
     * @param null|string $key
     * @return Container
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function registerSingleton($class, ?string $key = null): self
    {
        $rClass = new \ReflectionClass($class);
        $key = $key ?? $rClass->getName();
        if ($rClass->isInstantiable() && false === \array_search(md5($key), self::$stack)) {
            self::$stack[md5($key)] = $class;
        } elseif (!$rClass->isInstantiable()) {
            throw new \Exception(
                'Class ' . $rClass->getName() . ' can\'t be registered as a singleton as it is not instantiable.'
            );
        }
        return $this;
    }

    /**
     * Build container's middleware
     * @return Container
     * @throws \Exception
     */
    public function build(): self
    {
        try {
            $this->registerSingleton(new Mapper\PhpMapper(), 'mapper')
                ->runMapper()
                ->register(ConfiguratorInterface::class)
                ->register(RouterInterface::class);
            return $this;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function start(): void
    {
        $router = \array_filter(self::$stack, function ($el) {
            return $el instanceof Router;
        });
        if (count($router) === 1) {
            $router = \current($router);
        } else {
            throw new \Exception('No Router object passed to the container.');
        }
        /** @var RouterInterface $router */
        /** @var Route $route */
        $route = $router->findRoute($this->request);
        if (is_null($route)) {
            /** @var Response $response */
            $response = new HtmlResponse();
            $response->render('Page not found!', 404);
            die();
        }
        $this->registerSingleton($route)
            ->register(ConfiguratorInterface::class);
        $class = $route->getController();
        $method = $route->getMethod();
        $class = new \ReflectionClass($class);
        $args = ($class->getConstructor()) ? $class->getConstructor()->getParameters() : [];
        \array_walk($args, function (\ReflectionParameter &$param) {
            $param = $this->getMapped($param->getClass());
            if (self::$stack[md5($param->getName())]) {
                $param = self::$stack[md5($param->getName())];
            } else {
                throw new \Exception('Class parameter not declared in container.');
            }
        });
        $rMethod = $class->getMethod($method);
        $rMethodArgs = $rMethod->getParameters();
        \array_walk($rMethodArgs, function (\ReflectionParameter &$param) {
            $param = $this->getMapped($param->getClass());
            if (!isset(self::$stack[md5($param->getName())])) {
                $this->register($param->getName());
            }
            $param = self::$stack[md5($param->getName())];
        });
        $instance = $class->newInstanceArgs($args);
        $rMethod->invokeArgs($instance, $rMethodArgs);
    }

    /**
     * @param \ReflectionClass $class
     * @return \ReflectionClass
     * @throws \Exception
     * @throws \ReflectionException
     */
    private function getMapped(\ReflectionClass $class): \ReflectionClass
    {
        if (!$class->isInstantiable()) {
            if (isset($this->mapper[$class->getName()])) {
                return new \ReflectionClass($this->mapper[$class->getName()]);
            } else {
                throw new \Exception('Mapping not found for class ' . $class->getName());
            }
        } else {
            return $class;
        }
    }

    /**
     * @param string $class_name
     * @return mixed
     * @throws ContainerDependencyMissingException
     */
    public function getInstance(string $class_name)
    {
        $key = md5($class_name);
        if (\array_key_exists($key, self::$stack)) {
            return self::$stack[$key];
        } else {
            throw new ContainerDependencyMissingException($class_name);
        }
    }
}
