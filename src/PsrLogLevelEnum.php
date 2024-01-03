<?php

namespace Cl\Log;

use Psr\Log\LogLevel;

use Cl\Enum\EnumJsonSerializableTrait;
use Cl\Enum\EnumFromNameInterface;
use Cl\Enum\EnumFromNameTrait;

enum PsrLogLevelEnum: string implements EnumFromNameInterface
{
    use EnumJsonSerializableTrait;
    use EnumFromNameTrait;

    case EMERGENCY = LogLevel::EMERGENCY;
    case ALERT = LogLevel::ALERT;
    case CRITICAL = LogLevel::CRITICAL;
    case ERROR = LogLevel::ERROR;
    case WARNING = LogLevel::WARNING;
    case NOTICE = LogLevel::NOTICE;
    case INFO = LogLevel::INFO;
    case DEBUG = LogLevel::DEBUG;
}