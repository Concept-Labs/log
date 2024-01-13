<?php
declare(strict_types=1);

namespace Cl\Log\Message;

use Cl\Able\Escapable\EscapableInterface;
use Cl\Able\Resettable\ResettableInterface;

class LogMessage implements LogMessageInterface, ResettableInterface
{
    use LogMessageIterpolateTrait;
    protected string $logLevel = '';
    protected string $message = '';
    protected string $processedMessage = '';
    protected mixed $context = [];

    protected \Throwable|null $contextException = null;


    /**
     * {@inheritDoc}
     */
    public function __construct(?string $logLevel = '',?string $message = '', mixed $context = [])
    {
        $this->set(logLevel: $logLevel, message: $message, context: $context);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $logLevel, string $message, mixed $context, ?bool $forceInterpolate = true): LogMessageInterface
    {
        $this->reset();

        $this->logLevel = $logLevel;
        $this->message  = $message;
        $this->context  = $context;

        if ($forceInterpolate) {
            $this->interpolate();
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(?bool $forceInterpolate = false, ?bool $escape = false): string
    {
        if ($forceInterpolate) {
            $this->interpolate();
        }

        return $escape 
            ? $this->escape($this->processedMessage) 
            : $this->processedMessage;
    }

    /**
     * Get the context
     *
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }
    
    /**
     * Get The log level
     *
     * @return string
     */
    public function getLogLevel(): string
    {
        return $this->logLevel;
    }

    /**
     * Escape the string
     *
     * @param string $string 
     * 
     * @return string
     */
    public function escape(string $string): string
    {
        // @TODO addslashes()?
        return $string;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): void
    {
        $this->logLevel = '';
        $this->message  = '';
        $this->processedMessage = '';
        $this->context  = null;
        $this->contextException = null;
    }

}

