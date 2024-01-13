<?php
declare(strict_types=1);

namespace Cl\Log\Tests\Container;

use Cl\Log\LoggerInterface;

use Cl\Log\Container\LoggerContainerInterface;
use Cl\Log\Container\LoggerContainer;
use Cl\Log\Container\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Cl\Log\LoggerContainer
 */
class LoggerContainerTest extends TestCase
{
    /**
     * Test adding and retrieving a logger.
     */
    public function testAttachAndGet(): void
    {
        $container = $this->createContainer();
        /** @var  LoggerInterface $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $container->attach('test', $logger);
        

        $this->assertSame($logger, $container->get('test'));
    }

    /**
     * Test checking if a logger exists.
     */
    public function testHas(): void
    {
        $container = $this->createContainer();
        $logger = $this->createMock(LoggerInterface::class);

        $container->attach('test', $logger);

        $this->assertTrue($container->has('test'));
        $this->assertFalse($container->has('nonexistent'));
    }

    /**
     * Test removing a logger.
     */
    public function testRemove(): void
    {
        $container = $this->createContainer();
        $logger = $this->createMock(LoggerInterface::class);

        $container->attach('test', $logger);

        $this->assertTrue($container->has('test'));

        $container->remove('test');

        $this->assertFalse($container->has('test'));
    }

    /**
     * Test trying to get a nonexistent logger.
     */
    public function testGetNonexistentLogger(): void
    {
        $this->expectException(NotFoundException::class);

        $container = $this->createContainer();
        $container->get('nonexistent');
    }

    /**
     * Create a LoggerContainer instance for testing.
     *
     * @return LoggerContainerInterface
     */
    private function createContainer(): LoggerContainerInterface
    {
        return new LoggerContainer();
    }
}
