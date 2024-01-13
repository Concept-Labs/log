<?php
namespace Cl\Log\Notify;

interface LoggerNotifyInterface
{
    public function notify(string $message) : void;
}