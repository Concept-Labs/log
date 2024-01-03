<?php
declare(strict_types=1);
namespace Cl\Log\Syslog;

use Cl\Log\PsrLogLevelEnum;

class SyslogLevelMap
{

    // protected PsrLogLevelEnum $psrLogLevelEnum;
    // protected SyslogLevelEnum $SyslogLevelEnum;

    /**
     * Map PSR LogLevel to Syslog
     *
     * @param string $psrLevel 
     * 
     * @return int
     */
    public static function mapLogLevelFromPsrToSyslog(string $psrLevel): int
    {
        $psrLogLevel = PsrLogLevelEnum::from($psrLevel);
        $syslogLevel = SyslogLevelEnum::fromName($psrLogLevel->name)?->value;

        return $syslogLevel;
    }
}