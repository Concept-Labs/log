<?php
namespace Cl\Log\LogLevel;

use Cl\Log\LogLevel\Exception\InvalidArgumentException;

use Stringable;

interface  LogLevelInterface
{
    /**
     * Assert Log level
     *
     * @param string|Stringable $level 
     * 
     * @return void
     * @throws InvalidArgumentException
     */
    function assertLogLevel(string|Stringable $level): void;
}