<?php
declare(strict_types=1);
namespace Cl\Log;

use Psr\Log\AbstractLogger as PsrAbstractLogger;
use Cl\Log\Able\LoggerResettableInterface;
use Cl\Log\Able\LoggerResettableTrait;
use Cl\Log\LogLevel\LogLevelInterface;
use Cl\Log\LogLevel\LogLevelTrait;
use Cl\Log\Message\LogMessage;

abstract class AbstractLogger extends PsrAbstractLogger implements LogLevelInterface, LoggerResettableInterface
{
    use LoggerResettableTrait;
    use LogLevelTrait;
    protected function interpolateMessage(string $message, ?array $context = [])
    {
        return (new LogMessage(message: $message, context: $context))->get();
    }
}
