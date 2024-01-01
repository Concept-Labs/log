<?php
namespace Cl\Log;

interface LoggerMessageInterface
{
    /**
     * Interpolates context values into the message placeholders.
     * 
     * @param string $message message string
     * @param array  $context context
     * 
     * @return string
     */
    function interpolate(string $message, ?array $context = []): string;
}