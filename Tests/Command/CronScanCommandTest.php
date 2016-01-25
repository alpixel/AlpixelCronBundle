<?php


// namespace Alpixel\Bundle\CronBundle\Tests\Command;

// use Alpixel\Bundle\CronBundle\Command\CronScanCommand;
// use Symfony\Component\Console\Application;
// use Symfony\Component\Console\Helper\HelperSet;
// use Symfony\Component\Console\Tester\CommandTester;
// use Symfony\Component\DependencyInjection\ContainerInterface;

// class CronScanCommandTest extends \PHPUnit_Framework_TestCase
// {
    // public function testExecute()
    // {
    //     $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    //     $commandTester = $this->createCommandTester($this->getContainer('user'));

    //     $exitCode = $commandTester->execute(array(
    //         'command' => 'cron:scan',
    //     ), array(
    //         'decorated' => false,
    //         'interactive' => false,
    //     ));

    //     $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
    //     $this->assertRegExp('/Finished scanning for cron jobs/', $commandTester->getDisplay());
    // }

    // private function createCommandTester(ContainerInterface $container, Application $application = null)
    // {
    //     if (null === $application) {
    //         $application = new Application();
    //     }

    //     $application->setAutoExit(false);

    //     $command = new CronScanCommand();
    //     $command->setContainer($container);

    //     $application->add($command);

    //     return new CommandTester($application->find('cron:scan'));
    // }

    // private function getContainer($username)
    // {
    //     $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

    //     $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
    //     $container->set('doctrine.orm.entity_manager', $om);

    //     $manipulator = $this->getMockBuilder('Alpixel\Bundle\CronBundle\Util\CronCommandManipulatorTest')
    //         ->disableOriginalConstructor()
    //         ->getMock();

    //     return $container;
    // }

// }
