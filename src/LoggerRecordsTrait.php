<?php
declare(strict_types=1);
namespace Cl\Log;

use Cl\Log\Message\LogMessage;

trait LoggerRecordsTrait
{
    public array $records = [];
    public array $recordsByLevel = [];

    public function addRecord($level, string|\Stringable $message, array $context = []): void
    {
        $record = [
            'level' => $level,
            'message' => (new LogMessage(message: $message, context: $context))->get(),
            'context' => $context,
        ];

        $this->recordsByLevel[$record['level']][] = $record;
        $this->records[] = $record;
    }

    /**
     * @param string $level
     * 
     * @return bool
     */
    public function hasRecords($level)
    {
        return isset($this->recordsByLevel[$level]);
    }

    /**
     * @param array $record
     * @param string $level
     * 
     * @return bool
     */
    public function hasRecord($record, $level)
    {
        if (is_string($record)) {
            $record = ['message' => $record];
        }

        return $this->hasRecordThatPasses(
            function ($rec) use ($record) {
                if ($rec['message'] !== $record['message']) {
                    return false;
                }
                if (isset($record['context']) && $rec['context'] !== $record['context']) {
                    return false;
                }
                return true;
            }, 
            $level
        );
    }

    /**
     * @param string $message
     * @param string $level
     * 
     * @return bool
     */
    public function hasRecordThatContains($message, $level)
    {
        return $this->hasRecordThatPasses(fn ($rec) => str_contains($rec['message'], $message), $level);
    }

    /**
     * @param string $regex
     * @param string $level
     * 
     * @return bool
     */
    public function hasRecordThatMatches($regex, $level)
    {
        return $this->hasRecordThatPasses(fn ($rec) => preg_match($regex, $rec['message']) > 0, $level);
    }

    /**
     * @param callable $predicate
     * @param string $level
     * 
     * @return bool
     */
    public function hasRecordThatPasses(callable $predicate, $level)
    {
        if (!isset($this->recordsByLevel[$level])) {
            return false;
        }
        foreach ($this->recordsByLevel[$level] as $i => $rec) {
            if ($predicate($rec, $i)) {
                return true;
            }
        }

        return false;
    }

    public function hasEmergency($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::EMERGENCY->value);
    }

    public function hasAlert($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::ALERT->value);
    }

    public function hasCritical($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::CRITICAL->value);
    }

    public function hasError($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::ERROR->value);
    }

    public function hasWarning($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::WARNING->value);
    }

    public function hasNotice($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::NOTICE->value);
    }

    public function hasInfo($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::INFO->value);
    }

    public function hasDebug($record): bool
    {
        return $this->hasRecord($record, PsrLogLevelEnum::DEBUG->value);
    }

    public function hasEmergencyRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::EMERGENCY->value);
    }

    public function hasAlertRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::ALERT->value);
    }

    public function hasCriticalRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::CRITICAL->value);
    }

    public function hasErrorRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::ERROR->value);
    }

    public function hasWarningRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::WARNING->value);
    }

    public function hasNoticeRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::NOTICE->value);
    }

    public function hasInfoRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::INFO->value);
    }

    public function hasDebugRecords(): bool
    {
        return $this->hasRecords(PsrLogLevelEnum::DEBUG->value);
    }

    public function hasEmergencyThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::EMERGENCY->value);
    }

    public function hasAlertThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::ALERT->value);
    }

    public function hasCriticalThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::CRITICAL->value);
    }

    public function hasErrorThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::ERROR->value);
    }

    public function hasWarningThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::WARNING->value);
    }

    public function hasNoticeThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::NOTICE->value);
    }

    public function hasInfoThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::INFO->value);
    }

    public function hasDebugThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, PsrLogLevelEnum::DEBUG->value);
    }

    public function hasEmergencyThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::EMERGENCY->value);
    }

    public function hasAlertThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::ALERT->value);
    }

    public function hasCriticalThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::CRITICAL->value);
    }

    public function hasErrorThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::ERROR->value);
    }

    public function hasWarningThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::WARNING->value);
    }

    public function hasNoticeThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::NOTICE->value);
    }

    public function hasInfoThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::INFO->value);
    }

    public function hasDebugThatMatches(string $regex): bool
    {
        return $this->hasRecordThatMatches($regex, PsrLogLevelEnum::DEBUG->value);
    }

    public function hasEmergencyThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::EMERGENCY->value);
    }

    public function hasAlertThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::ALERT->value);
    }

    public function hasCriticalThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::CRITICAL->value);
    }

    public function hasErrorThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::ERROR->value);
    }

    public function hasWarningThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::WARNING->value);
    }

    public function hasNoticeThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::NOTICE->value);
    }

    public function hasInfoThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::INFO->value);
    }

    public function hasDebugThatPasses(callable $predicate): bool
    {
        return $this->hasRecordThatPasses($predicate, PsrLogLevelEnum::DEBUG->value);
    }
}
