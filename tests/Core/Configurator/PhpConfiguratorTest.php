<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core\Configurator;


use App\Core\Configurator;
use App\Core\Configurator\PhpConfigurator;
use PHPUnit\Framework\TestCase;

class PhpConfiguratorTest extends TestCase
{
    public function testInstance()
    {
        $configurator = new \ReflectionClass(PhpConfigurator::class);

        $this->assertInstanceOf(Configurator::class, $configurator->newInstance());
        $this->assertTrue($configurator->implementsInterface(Configurator\ConfiguratorInterface::class));
    }

    public function testConfigFiles()
    {
        $configurator = new \ReflectionClass(PhpConfigurator::class);
        $configFiles = $configurator->getProperty('config_files');
        $configFiles->setAccessible(true);

        $this->assertInstanceOf(\DirectoryIterator::class, $configFiles->getValue($configurator->newInstance()));
    }

    public function testConfiguration()
    {
        $configurator = new \ReflectionClass(PhpConfigurator::class);
        $method = $configurator->getMethod('loadConfigurations');
        $method->setAccessible(true);
        $configurations = $configurator->getProperty('configurations');
        $configurations->setAccessible(true);

        $instance = $configurator->newInstance();
        $method->invoke($instance);

        $this->assertInstanceOf(\ArrayObject::class, $configurations->getValue($instance));
        // Test single value
        $configurations->setValue($instance, new \ArrayObject(['test' => 'config']));
        $this->assertTrue($instance->has('test'));
        $this->assertEquals('config', $instance->get('test'));
        // Test array value
        $configurations->setValue($instance, new \ArrayObject(['test' => ['config1' => 1, 'config2' => 2]]));
        $this->assertTrue($instance->has('test', 'config1'));
        $this->assertEquals(1, $instance->get('test', 'config1'));
    }
}
