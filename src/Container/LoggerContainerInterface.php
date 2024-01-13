<?php
declare(strict_types=1);

namespace Cl\Log\Container;

use Cl\Log\LoggerInterface;

use Cl\Log\Container\Exception\LoggerAlreadyExistsException;
use Cl\Log\Container\Exception\LoggerNotFoundException;

/**
 * Interface LoggerContainerInterface
 *
 * Defines a container for managing and accessing multiple loggers.
 */
interface LoggerContainerInterface
{
    /**
     * Gets a logger instance by its alias.
     *
     * @param string $alias The alias of the logger.
     *
     * @return LoggerInterface The logger instance.
     * @throws LoggerNotFoundException If the logger with the given alias is not found.
     */
    public function get(string $alias): LoggerInterface;

    /**
     * Checks if a logger with the given alias exists in the container.
     *
     * @param string $alias The alias of the logger.
     *
     * @return bool True if the logger exists, false otherwise.
     */
    public function has(string $alias): bool;

    /**
     * Attaches a logger instance to the container with the specified alias.
     *
     * @param string          $alias  The alias for the logger.
     * @param LoggerInterface $logger The logger instance.
     * 
     * @return void
     * @throws LoggerAlreadyExistsException If the logger with the given alias already exists.
     */
    public function attach(string $alias, LoggerInterface $logger);

    /**
     * Removes a logger from the container by its alias.
     *
     * @param string $alias The alias of the logger to be removed.
     * 
     * @return void
     */
    public function remove(string $alias): void;
}