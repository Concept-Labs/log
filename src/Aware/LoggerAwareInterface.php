<?php
namespace Cl\Log\Logger\Aware;

use Psr\Log\LoggerInterface;

/**
 * Describes a logger-aware instance.
 */
interface LoggerAwareInterface extends \Psr\Log\LoggerAwareInterface
{
    /**
     * Gets the logger instance
     *
     * @return LoggerInterface
     */
    function getLogger(): LoggerInterface;

}