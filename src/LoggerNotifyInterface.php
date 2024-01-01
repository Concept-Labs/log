<?php
namespace Cl\Log;

interface LoggerNotifyInterface
{
    public function notify(string $message) : void;
}