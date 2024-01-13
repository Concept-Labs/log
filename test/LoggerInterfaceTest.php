<?php
namespace Cl\Log\Test;

use Cl\Log\LogLevel\LogLevelTrait;
use Cl\Log\Message\LogMessage;
use Psr\Log\LogLevel;
use Psr\Log\Test\LoggerInterfaceTest as PsrLoggerInterfaceTest;
// use Cl\Log\Test\TestLogger;

use Psr\Log\Test\TestLogger as PsrTestLogger;


/**
 * Dummy Logger
 */
class ClTestLogger extends PsrTestLogger
{
    use LogLevelTrait;

    /**
     * Aggregate messages
     *
     * @return array
     */
    public function getLogs(): array
    {
        $messages = [];
        foreach ($this->records as $record) {
            $messages[] = $record->get();
        };
        return $messages;
    }

    /**
     * Dummy log save
     *
     * @param string             $level 
     * @param string|\Stringable $message 
     * @param array              $context 
     * 
     * @return void
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->assertLogLevel($level);
        
        $record = new LogMessage($level, $message, $context);
        
        $this->recordsByLevel[$record->getLogLevel()][] = $record;
        $this->records[] = $record;
    }
}

/**
 * @covers Cl\Log\LoggerInterface
*/
class LoggerInterfaceTest extends PsrLoggerInterfaceTest
{
    protected $logger = null;

    /**
     * Abstract method implementation
     *
     * @return void
     */
    public function getLogger()
    {
        if (!$this->logger) {
            $this->logger = new ClTestLogger();
        }
        $this->logger->reset();
        return $this->logger;
    }

    /**
     * Abstract method implementation
     *
     * @return array
     */
    public function getLogs()
    {
        return $this->logger->getLogs();
    }

    /**
     * Separate dataprovider to fix PHPUnit non static data providers deprecation
     * 
     * @see Psr\Log\Test\LoggerInterfaceTest::provideLevelsAndMessages()
     *
     */
    public static function provideLevelsAndMessagesStatic()
    {
        return [
            LogLevel::EMERGENCY => [LogLevel::EMERGENCY, 'message of level emergency with context: {user}'],
            LogLevel::ALERT => [LogLevel::ALERT, 'message of level alert with context: {user}'],
            LogLevel::CRITICAL => [LogLevel::CRITICAL, 'message of level critical with context: {user}'],
            LogLevel::ERROR => [LogLevel::ERROR, 'message of level error with context: {user}'],
            LogLevel::WARNING => [LogLevel::WARNING, 'message of level warning with context: {user}'],
            LogLevel::NOTICE => [LogLevel::NOTICE, 'message of level notice with context: {user}'],
            LogLevel::INFO => [LogLevel::INFO, 'message of level info with context: {user}'],
            LogLevel::DEBUG => [LogLevel::DEBUG, 'message of level debug with context: {user}'],
        ];
    }

    /**
     * Overrided to fix PHPUnit non static data provider deprecation
     * 
     * @see Psr\Log\Test\LoggerInterfaceTest::testLogsAtAllLevels()
     * 
     * @dataProvider provideLevelsAndMessagesStatic
     */
    public function testLogsAtAllLevels($level, $message)
    {
        parent::testLogsAtAllLevels($level, $message);
    }

    
}