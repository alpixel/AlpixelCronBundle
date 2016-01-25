<?php

namespace Alpixel\Bundle\CronBundle\Tests\Command;

use Alpixel\Bundle\CronBundle\Command\CronScanCommand;
use Alpixel\Bundle\CronBundle\Entity\CronJob;
use Alpixel\Bundle\CronBundle\Entity\Repository\CronJobRepository;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CronScanCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getContainer());

        $exitCode = $commandTester->execute(array(
            'command' => 'cron:scan',
        ), array(
            'decorated' => false,
            'interactive' => false,
        ));

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

    private function getContainer()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $container
            ->expects($this->once())
            ->method('get')
            ->with('doctrine.orm.entity_manager')
            ->will($this->returnValue($this->getMockManager()));

        $container
            ->expects($this->once())
            ->method('get')
            ->with('annotation_reader')
            ->will($this->returnValue($this->getMockReader()));

        return $container;
    }

    private function getMockManager()
    {
        $mockManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $cronjobRepository = $this
            ->getMockBuilder(CronJobRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cronJob = $this->getMock(CronJob::class);
        $cronJob->expects($this->once())
            ->method('getCommand')
            ->will($this->returnValue('i-should-do-this.sh'));

        $cronjobRepository->expects($this->once())
            ->method('getKnownJobs')
            ->will($this->returnValue(['i-should-do-this.sh']));

        $mockManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($cronjobRepository));

        return $mockManager;
    }

    private function getMockReader()
    {
        $mockReader = $this
            ->getMockBuilder('\Doctrine\Common\Annotations\AnnotationReader')
            ->disableOriginalConstructor()
            ->getMock();

        return $mockReader;
    }
}
