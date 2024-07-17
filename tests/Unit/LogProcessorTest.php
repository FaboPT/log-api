<?php

namespace App\Tests\Unit;

use App\Entity\LogEntry;
use App\Service\LogProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogProcessorTest extends KernelTestCase
{
    private const string FILE_PATH = __DIR__.'/../../resources/logs.log';

    /**
     * @throws \Exception
     */
    public function testProcessLogFile(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $em = $container->get('doctrine')->getManager();

        $logProcessor = new LogProcessor($em);
        $logProcessor->process(self::FILE_PATH);

        $repository = $em->getRepository(LogEntry::class);
        $count = $repository->count([]);

        $this->assertGreaterThan(0, $count);
    }
}
