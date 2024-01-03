<?php
declare(strict_types=1);

use Cl\Log\Syslog\Logger;
use PHPUnit\Framework\TestCase;
use Cl\Log\Syslog\SyslogLevelMap;
use Cl\Log\Syslog\SyslogLevelEnum;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @covers Cl\Log\Syslog\SyslogLevelMap
 * @covers Cl\Log\Syslog\SyslogLevelEnum
 * @covers Cl\Log\PsrLogLevelEnum
 */
final class SyslogTest extends TestCase
{
    protected LoggerInterface $sysLogger;

    public function setUp(): void
    {
        $this->sysLogger = new Logger();
    }
    public function testSysloggerInstance(): void
    {
        $this->assertInstanceOf(LoggerInterface::class, $this->sysLogger);
    }

}
