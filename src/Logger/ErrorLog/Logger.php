<?php
declare(strict_types=1);
namespace Cl\Log\ErrorLog;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{

    private $messageType = 0;
    private $destination = null;
    private $additionalHeaders = null;


    /**
     * {@inheritDoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        error_log();
    }

}