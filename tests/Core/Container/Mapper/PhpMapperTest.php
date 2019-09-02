<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core\Container;


use App\Core\Container\Mapper;
use App\Core\Container\Mapper\PhpMapper;
use App\Core\Router;
use App\Core\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

class PhpMapperTest extends TestCase
{
    public function testInstance()
    {
        $class = new \ReflectionClass(PhpMapper::class);

        $this->assertInstanceOf(Mapper::class, $class->newInstance());
    }

    public function testInitializeMapper()
    {
        $mapper = new \ReflectionClass(PhpMapper::class);
        $mappings = $mapper->getProperty('mapping');
        $mappings->setAccessible(true);

        $instance = $mapper->newInstance();

        $this->assertInstanceOf(\ArrayObject::class, $mappings->getValue($instance));
        $this->assertEquals(1, $mappings->getValue($instance)->count());
        $this->assertTrue($mappings->getValue($instance)->offsetExists(RouterInterface::class));
        $this->assertEquals(Router::class, $mappings->getValue($instance)->offsetGet(RouterInterface::class));
    }

    public function testAddGetMapping()
    {
        $mapper = new \ReflectionClass(PhpMapper::class);
        $addMethod = $mapper->getMethod('addMapping');
        $addMethod->setAccessible(true);

        $instance = $mapper->newInstance();

        $addMethod->invokeArgs($instance, ['test', \DateTime::class]);

        $this->assertEquals(\DateTime::class, $instance->getMapping('test'));
    }
}
