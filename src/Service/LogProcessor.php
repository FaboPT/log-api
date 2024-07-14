<?php

namespace App\Service;

use App\Utils\ReadFile;
use Doctrine\ORM\EntityManagerInterface;

class LogProcessor
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws \Exception
     */
    public function process(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException('Log file does not exist.');
        }

        $handle = ReadFile::readLogFile($filePath);
        $logEntries = ReadFile::parseLogFile($handle);
        $this->saveLogEntries($logEntries);
    }

    private function saveLogEntries($logEntries): void
    {
        foreach ($logEntries as $logEntry) {
            $this->entityManager->persist($logEntry);
        }

        $this->entityManager->flush();
    }
}
