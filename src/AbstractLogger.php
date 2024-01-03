<?php
declare(strict_types=1);
namespace Cl\Log;

use Cl\Log\Exception\InvalidContextException;
use Cl\Log\Exception\InvalidRegexException;
use Cl\Log\Message\LogMessage;
use Psr\Log\AbstractLogger as PsrAbstractLogger;

abstract class AbstractLogger extends PsrAbstractLogger
{
    public function __construct()
    {
    }

    protected function interpolateMessage(string $message, ?array $context = [])
    {
        return (new LogMessage(message: $message, context: $context))->get();
    }
}
