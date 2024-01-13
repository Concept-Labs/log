<?php
namespace Cl\Log\LogLevel;

use Cl\Log\LogLevel\Exception\InvalidArgumentException;
use Cl\Log\PsrLogLevelEnum;

use Stringable;
use Throwable;

trait LogLevelTrait
{
    public function assertLogLevel(string|Stringable $level): void
    {
        try {
            $case = PsrLogLevelEnum::from((string)$level);
        } catch (Throwable $e) {
            throw new InvalidArgumentException(sprintf('Invalid Log level "%s"', (string)$level));
        }
    }
}