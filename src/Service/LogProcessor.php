<?php

namespace App\Service;

use App\Entity\LogEntry;
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
            throw new \Exception('Log file does not exist.');
        }

        $handle = $this->openLogFile($filePath);
        $logEntries = $this->parseLogFile($handle);
        $this->saveLogEntries($logEntries);
    }

    private function openLogFile(string $filePath)
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new \Exception('Unable to open log file.');
        }

        return $handle;
    }

    /**
     * @throws \Exception
     */
    private function parseLogFile($handle): array
    {
        $logEntries = [];

        while (($line = fgets($handle)) !== false) {
            // Skip empty lines and lines that are comments
            if ('' === trim($line)) {
                continue;
            }

            // Parse the log line
            $logEntry = $this->parseLogLine($line);
            if ($logEntry) {
                $logEntries[] = $logEntry;
            }
        }

        fclose($handle);

        return $logEntries;
    }

    private function saveLogEntries($logEntries): void
    {
        foreach ($logEntries as $logEntry) {
            $this->entityManager->persist($logEntry);
        }

        $this->entityManager->flush();
    }

    private function parseLogLine(bool|string $line): ?LogEntry
    {
        $pattern = '/^(?P<serviceName>[\w-]+) - - \[(?P<timestamp>[^\]]+)\] "(?P<method>\w+) (?P<url>[^"]+) HTTP\/1\.1" (?P<statusCode>\d+)/';

        if (preg_match($pattern, $line, $matches)) {
            $logEntry = new LogEntry();
            $logEntry->setServiceName($matches['serviceName']);
            $logEntry->setStatusCode((int) $matches['statusCode']);
            $logEntry->setTimestamp(\DateTime::createFromFormat('d/M/Y:H:i:s O', $matches['timestamp']));
            $logEntry->setMethod($matches['method']);
            $logEntry->setUrl($matches['url']);

            return $logEntry;
        }

        // Return null if the line does not match the expected format
        return null;
    }
}
