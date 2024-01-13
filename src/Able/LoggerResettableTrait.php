<?php
declare(strict_types=1);

namespace Cl\Log\Able;

trait LoggerResettableTrait
{
    /**
     * Reset Logger data
     *
     * @return void
     */
    public function reset(): void
    {
        $this->records = [];
        $this->recordsByLevel = [];
    }
}
