<?php

namespace Alpixel\Bundle\CronBundle\Tests\Command;

use Alpixel\Bundle\CronBundle\Command\CronScanCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CronScanCommandTest extends KernelTestCase
{
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }

    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->container);

        $exitCode = $commandTester->execute([
            'command' => 'cron:scan',
        ], [
            'decorated'   => false,
            'interactive' => false,
        ]);

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Finished scanning for cron jobs/', $commandTester->getDisplay());
    }

    private function createCommandTester(ContainerInterface $container, Application $application = null)
    {
        if (null === $application) {
            $application = new Application();
        }
        $application->setAutoExit(false);

        $command = new CronScanCommand();
        $command->setContainer($container);

        $application->add($command);

        return new CommandTester($application->find('cron:scan'));
    }
}
