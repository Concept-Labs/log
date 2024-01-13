<?php
declare(strict_types=1);

namespace Cl\Log\Container;

use Cl\Log\Container\Exception\NotFoundException;
use Cl\Log\Container\Exception\AlreadyExistsException;
use Cl\Log\LoggerInterface;

class LoggerContainer implements LoggerContainerInterface
{
    private $_loggers = [];

    public function get(string $alias): LoggerInterface
    {
        return match (true) {
            $this->has($alias) => $this->_loggers[$alias],
            default=> throw new NotFoundException(sprintf('Logger "%s" not found', $alias)),
        };
    }

    public function has(string $alias): bool
    {
        return isset($this->_loggers[$alias]);
    }

    public function attach(string $alias, LoggerInterface $logger): void
    {
        if ($this->has($alias)) {
            throw new AlreadyExistsException(sprintf('Container already contains the logger with alias "%s"'));
        }

        $this->_loggers[$alias] = $logger;
    }

    public function remove(string $alias): void
    {
        if ($this->has($alias)) {
            unset($this->_loggers[$alias]);
        }
    }
}