<?php

namespace App\Command;

use App\Service\LogProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'process-log',
    description: 'Processes a log file and stores entries in the database',
)]
class ProcessLogFileCommand extends Command
{
    private const string FILE_PATH = __DIR__.'/../../resources/logs.log';

    public function __construct(private readonly LogProcessor $logProcessor)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->logProcessor->process(self::FILE_PATH);
            $output->writeln('Log file processed successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
