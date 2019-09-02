<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace Core;


use App\Core\DataProcessor;
use App\Core\DataProcessor\Driver\SqliteDriver;
use PHPUnit\Framework\TestCase;

class DataProcessorTest extends TestCase
{
    public function testInstance()
    {
        $dp = new \ReflectionClass(DataProcessor::class);
        $this->assertTrue($dp->implementsInterface(DataProcessor\DataProcessorInterface::class));
    }

    public function testDriver()
    {
        $dp = new \ReflectionClass(SqliteDriver::class);
        $this->assertInstanceOf(DataProcessor::class, $dp->newInstanceWithoutConstructor());
        $this->assertTrue($dp->implementsInterface(DataProcessor\DataProcessorInterface::class));
        $this->assertTrue($dp->hasMethod('connect'));
        $this->assertTrue($dp->hasMethod('disconnect'));
    }
}
