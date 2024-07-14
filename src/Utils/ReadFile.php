<?php

namespace App\Utils;

use App\Entity\LogEntry;

class ReadFile
{
    public static function readLogFile(string $filePath)
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new \RuntimeException('Unable to open log file.');
        }

        return $handle;
    }

    public static function parseLogFile($handle): array
    {
        $logEntries = [];

        while (($line = fgets($handle)) !== false) {
            // Skip empty lines and lines that are comments
            if ('' === trim($line)) {
                continue;
            }

            // Parse the log line
            $logEntry = self::parseLogLine($line);
            if ($logEntry) {
                $logEntries[] = $logEntry;
            }
        }

        fclose($handle);

        return $logEntries;
    }

    private static function parseLogLine(bool|string $line): ?LogEntry
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
