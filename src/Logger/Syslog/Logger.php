<?php
declare(strict_types=1);
namespace Cl\Log\Syslog;

use Cl\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    private $syslogIdent;

    /**
     * Logger constructor.
     *
     * @param string $syslogIdent Ident to use for syslog
     */
    public function __construct($syslogIdent = 'Cl\Syslog')
    {
        $this->syslogIdent = $syslogIdent;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level 
     * @param string $message 
     * @param array  $context 
     *
     * @return void
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        // Map PSR-3 log levels to syslog log levels
        $syslogLevel = $this->mapLogLevelFromPsrToSyslog($level);

        $finalMessage = $this->interpolateMessage((string)$message, $context);

        openlog($this->syslogIdent, LOG_PID | LOG_PERROR, LOG_USER);
        syslog($syslogLevel, $finalMessage);
        closelog();
    }

    /**
     * Map PSR LogLevel to Syslog
     *
     * @param string $psrLevel 
     * 
     * @return int
     */
    public function mapLogLevelFromPsrToSyslog(string $psrLevel): int
    {
        return SyslogLevelMap::mapLogLevelFromPsrToSyslog($psrLevel);

        // $psrLogLevel = PsrLogLevelEnum::from($psrLevel);
        // $syslogLevel = SyslogLevelEnum::fromName($psrLogLevel->name)?->value;

        // return $syslogLevel;
    }

    
}
