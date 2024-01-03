<?php
declare(strict_types=1);
namespace Cl\Log\Message;

class LogMessage implements LogMessageInterface
{
    use LogMessageIterpolateTrait;
    protected string $message = '';
    protected string $processedMessage = '';
    protected mixed $context = [];

    protected \Throwable|null $contextException = null;


    /**
     * Log message constructor
     *
     * @param string $message the message string
     * @param mixed  $context The context with replacement values
     */
    public function __construct(string $message = '', mixed $context = [])
    {
        $this->set(message: $message, context: $context);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $message, mixed $context): LogMessageInterface
    {
        $this->message = $message;
        $this->context = $context;
        return $this->interpolate();
    }

    /**
     * {@inheritDoc}
     */
    public function get(?bool $escape = false): string
    {
        return $escape ? $this->escape($this->processedMessage) : $this->processedMessage;
    }

    /**
     * Escape the string
     *
     * @param string $string 
     * 
     * @return string
     */
    public static function escape(string $string): string
    {
        // @TODO addslashes()?
        return $string;
    }

    

}

