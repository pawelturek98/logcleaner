<?php

declare(strict_types=1);

namespace LogCleaner\Strategy\DTO;

use InvalidArgumentException;

class FileCleanerStrategyDTO implements LogCleanerStrategyDTOInterface
{
    private int $timePeriod;
    private string $path;

    public function getTimePeriod(): int
    {
        return $this->timePeriod;
    }

    public function setTimePeriod(int $timePeriod): void
    {
        if ($timePeriod < 0) {
            throw new InvalidArgumentException("Time period should not be lesser than 0!");
        }

        $this->timePeriod = $timePeriod;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
