<?php

namespace Alpixel\Bundle\CronBundle\Tests\Command;

use Alpixel\Bundle\CronBundle\Command\CronScanCommand;
use Alpixel\Bundle\CronBundle\Tests\Fixtures\CronTestCommand;
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

        $commandTester = $this->createCommandTester($this->container, [new CronTestCommand]);

        $exitCode = $commandTester->execute([
            'command' => 'cron:scan',
        ], [
            'decorated'   => false,
            'interactive' => false,
        ]);

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Finished scanning for cron jobs/', $commandTester->getDisplay());

        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $command = $entityManager->getRepository('CronBundle:CronJob')->findOneByCommand('my_cron_test');

        $this->assertNotNull($command, 'Can\'t find newly created command in database');
    }

    private function createCommandTester(ContainerInterface $container, $extraCommands = [])
    {
        $application = new Application();
        $application->setAutoExit(false);

        $command = new CronScanCommand();
        $command->setContainer($container);

        foreach ($extraCommands as $extraCommand) {
            $extraCommand->setContainer($container);
            $application->add($extraCommand);
        }

        $application->add($command);

        return new CommandTester($application->find('cron:scan'));
    }
}
