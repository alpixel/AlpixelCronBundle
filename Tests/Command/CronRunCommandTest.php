<?php

namespace Alpixel\Bundle\CronBundle\Tests\Command;

use Alpixel\Bundle\CronBundle\Command\CronRunCommand;
use Alpixel\Bundle\CronBundle\Tests\Fixtures\CronTestCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CronRunCommandTest extends KernelTestCase
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
            'command' => 'cron:run',
        ], [
            'decorated'   => false,
            'interactive' => false,
        ]);

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Running 1 jobs/', $commandTester->getDisplay());
        $this->assertRegExp('/Cron run completed in/', $commandTester->getDisplay());
        $this->assertEquals('ok', file_get_contents(__DIR__.'/../Fixtures/app/cache/cron_result.log'));
    }

    private function createCommandTester(ContainerInterface $container, Application $application = null)
    {
        if (null === $application) {
            $application = new Application();
        }
        $application->setAutoExit(false);

        $command = new CronRunCommand();
        $command->setContainer($container);

        $testCommand = new CronTestCommand();
        $testCommand->setContainer($container);

        $application->add($testCommand);
        $application->add($command);

        return new CommandTester($application->find('cron:run'));
    }
}
