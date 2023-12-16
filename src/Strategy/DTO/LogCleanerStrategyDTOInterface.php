<?php

declare(strict_types=1);

namespace LogCleaner\Strategy\DTO;

use DateTime;

interface LogCleanerStrategyDTOInterface
{
    public function getTimePeriod(): int;
    public function setTimePeriod(int $timePeriod): void;
}
