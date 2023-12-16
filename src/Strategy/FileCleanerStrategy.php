<?php

declare(strict_types=1);

namespace LogCleaner\Strategy;

use DateTime;
use InvalidArgumentException;
use LogCleaner\Exception\FileNotFoundException;
use LogCleaner\LogCleanerInterface;
use LogCleaner\Strategy\DTO\FileCleanerStrategyDTO;
use LogCleaner\Strategy\DTO\LogCleanerStrategyDTOInterface;

final class FileCleanerStrategy implements LogCleanerInterface
{
    public function clean(LogCleanerStrategyDTOInterface $dto): void
    {
        if (!$dto instanceof FileCleanerStrategyDTO) {
            throw new InvalidArgumentException(sprintf('DTO Must be instance of %s', FileCleanerStrategyDTO::class));
        }

        $path = $dto->getPath();

        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf( 'File %s does not exists', $path));
        }

        $filteredLines = array_filter(file($path), function(string $line) use ($dto): bool {
            $matchedDateTime = $this->getExtractedDateTimeFromLine($line);
            if (!$matchedDateTime) {
                return true;
            }

            $nowDateTime = new DateTime();
            $interval = date_diff($matchedDateTime, $nowDateTime);

            return $interval->days < $dto->getTimePeriod();
        });

        file_put_contents($path, implode('', $filteredLines));
    }

    private function getExtractedDateTimeFromLine(string $line): ?DateTime
    {
        $matches = null;
        if(preg_match('/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/', $line, $matches)) {
            $matches = new DateTime($matches[0]);
        }

        return $matches;
    }
}
