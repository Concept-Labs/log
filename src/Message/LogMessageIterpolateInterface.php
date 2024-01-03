<?php
declare(strict_types=1);
namespace Cl\Log\Message;

interface LogMessageIterpolateInterface
{
    /**
     * Interpolates context values into the message placeholders.
     * 
     * @return LogMessageInterface
     */
    public function interpolate(): LogMessageInterface;

}