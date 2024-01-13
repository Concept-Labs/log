<?php 
declare(strict_types=1);

namespace Cl\Log\File\Logger;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $message = "".$this->interpolate($message, $context);
    }
}