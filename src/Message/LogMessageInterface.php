<?php
declare(strict_types=1);
namespace Cl\Log\Message;

/**
 * The logger message interface
 */
interface LogMessageInterface extends LogMessageIterpolateInterface
{
    const PLACEHOLDER_OPEN_TAG = '{';
    const PLACEHOLDER_CLOSE_TAG = '}';
    const INVALID_PLACEHOLDER_REGEX_PATTERN = '/[^A-za-z0-9_\.]/sumix';
 
    /**
     * Sets the message and context
     *
     * @param string $message The message to be interpolated 
     * @param mixed  $context The context with actual values
     * 
     * @return LogMessageInterface
     */
    public function set(string $message, mixed $context): LogMessageInterface;

    /**
     * Get the interpolated string
     * 
     * @param ?bool $escape 
     *
     * @return string
     */
    public function get(?bool $escape = false): string;

}