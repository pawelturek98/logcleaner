<?php

declare(strict_types=1);

namespace Tests;

use DateInterval;
use DateTime;
use LogCleaner\Exception\FileNotFoundException;
use LogCleaner\LogCleanerContext;
use LogCleaner\LogCleanerInterface;
use LogCleaner\Strategy\DTO\FileCleanerStrategyDTO;
use LogCleaner\Strategy\DTO\LogCleanerStrategyDTOInterface;
use LogCleaner\Strategy\FileCleanerStrategy;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class FileLogCleanerTest extends TestCase
{
    private LogCleanerInterface $cleanerContext;
    private vfsStreamDirectory $vfsStreamRoot;
    private string $filePath;

    public function setUp(): void
    {
        $this->cleanerContext = new LogCleanerContext(new FileCleanerStrategy());
        $testDirPath = sprintf('%s/var/logs', dirname(__DIR__));

        $this->vfsStreamRoot = vfsStream::setup($testDirPath);
        $this->generateTestLog();
    }

    public function testCleaner(): void
    {
        $dto = new FileCleanerStrategyDTO();
        $dto->setPath($this->filePath);
        $dto->setTimePeriod(3);

        $now = new DateTime('now');
        $p1d = clone $now->sub(new DateInterval('P1D'));
        $p7d = clone $now->sub(new DateInterval('P7D'));

        $this->assertStringContainsString($p1d->format('Y-m-d'), file_get_contents($this->filePath));
        $this->assertStringContainsString($p7d->format('Y-m-d'), file_get_contents($this->filePath));

        $LogCleanerContext = new LogCleanerContext(new FileCleanerStrategy());
        $LogCleanerContext->clean($dto);

        $this->assertStringContainsString($p1d->format('Y-m-d'), file_get_contents($this->filePath));
        $this->assertStringNotContainsString($p7d->format('Y-m-d'), file_get_contents($this->filePath));
    }

    public function testInvalidConfig(): void
    {
        $dummyDTO = $this->createMock(LogCleanerStrategyDTOInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->cleanerContext->clean($dummyDTO);
    }

    public function testFileDoesntExists(): void
    {
        $dto = new FileCleanerStrategyDTO();
        $dto->setPath('../var/logs/notExists.log');
        $dto->setTimePeriod(7);

        $this->expectException(FileNotFoundException::class);
        $this->cleanerContext->clean($dto);
    }

    public function testNegativeTimePeriod(): void
    {
        $dto = new FileCleanerStrategyDTO();
        $this->expectException(\InvalidArgumentException::class);
        $dto->setTimePeriod(-1);
    }

    private function generateTestLog(): void
    {
        $today = new DateTime('now');

        $data = [];
        for ($i = 1; $i <= 30; $i++) {
            $dayBefore = $today->sub(new DateInterval('P1D'));
            $data[] = sprintf('[%s] %s', $dayBefore->format('Y-m-d H:i:s'), uniqid());
        }

        $this->filePath = vfsStream::newFile('test.log')
            ->at($this->vfsStreamRoot)
            ->setContent(implode(PHP_EOL, $data))
            ->url();
    }
}
