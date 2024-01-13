<?php
namespace Cl\Log\Logger\Aware;

use Psr\Log\LoggerInterface;

/**
 * Describes a logger-aware instance.
 */
trait LoggerAwareTrait
{
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger 
     * 
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Gets the logger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}