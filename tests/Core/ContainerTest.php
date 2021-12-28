<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core;

use App\Core\Container;
use App\Core\Exception\Container\ContainerDependencyMissingException;
use DateTime;
use DateTimeZone;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class DummyClass
{
    private DateTime $date;

    /**
     * DummyClass constructor.
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }
}

class ContainerTest extends TestCase
{
    /**
     * @return Container
     * @throws ReflectionException
     */
    public function testInstance(): Container
    {
        $container = new ReflectionClass(Container::class);
        /** @var Container $containerInstance */
        $containerInstance = $container->newInstanceWithoutConstructor();

        $this->assertInstanceOf(Container::class, $containerInstance);

        $this->assertTrue($container->hasMethod('start'));
        $this->assertTrue($container->hasMethod('build'));

        return $containerInstance;
    }

    /**
     * @depends testInstance
     *
     * @param Container $container
     * @throws ReflectionException
     * @throws ContainerDependencyMissingException
     * @throws Exception
     */
    public function testDependencyInjection(Container $container)
    {
        $dependency = new DateTime('2000-01-01 00:00:00', new DateTimeZone('UTC'));

        $container->registerSingleton($dependency);

        $container->register(DummyClass::class);

        $test = $container->getInstance(DummyClass::class);

        $this->assertInstanceOf(DateTime::class, $test->getDate());
        $this->assertEquals('01/01/2000 00:00:00', $test->getDate()->format('m/d/Y H:i:s'));

        $this->expectException(ContainerDependencyMissingException::class);
        $container->getInstance('othertest');
    }
}
