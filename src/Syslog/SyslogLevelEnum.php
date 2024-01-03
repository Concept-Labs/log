<?php

namespace Cl\Log\Syslog;

use Cl\Enum\EnumFromNameInterface;
use Cl\Enum\EnumFromNameTrait;
use Cl\Enum\EnumJsonSerializableTrait;

enum SyslogLevelEnum: int implements EnumFromNameInterface
{
    use EnumJsonSerializableTrait;
    use EnumFromNameTrait;
    
    case DEBUG = LOG_DEBUG;
    case INFO = LOG_INFO;
    case NOTICE = LOG_NOTICE;
    case WARNING = LOG_WARNING;
    case ERROR = LOG_ERR;
    case CRITICAL = LOG_CRIT;
    case ALERT = LOG_ALERT;
    case EMERGENCY = LOG_EMERG;

}