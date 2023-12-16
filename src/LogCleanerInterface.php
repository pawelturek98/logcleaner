<?php

declare(strict_types=1);

namespace LogCleaner;

use LogCleaner\Strategy\DTO\LogCleanerStrategyDTOInterface;

interface LogCleanerInterface
{
    public function clean(LogCleanerStrategyDTOInterface $dto): void;
}
