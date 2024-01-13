<?php
namespace Cl\Log\Deferred;

interface DeferredLoggerIntterface
{
    /**
     * Deferred logging
     *
     * @param mixed              $level 
     * @param string|\Stringable $message 
     * @param array              $context 
     * 
     * @return void
     * @throws \Cl\Log\Deferred\Exception\InvalidArgumentException
     */
    function logDeffered($level, string|\Stringable $message, array $context = []): void;
}