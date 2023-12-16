<?php

declare(strict_types=1);

namespace LogCleaner;

use LogCleaner\Strategy\DTO\LogCleanerStrategyDTOInterface;

class LogCleanerContext implements LogCleanerInterface
{
    public function __construct(
        private readonly LogCleanerInterface $strategy,
    ) { }

    public function clean(LogCleanerStrategyDTOInterface $dto): void
    {
        $this->strategy->clean($dto);
    }
}
