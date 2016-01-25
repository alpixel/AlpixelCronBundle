<?php

namespace Alpixel\Bundle\CronBundle\Tests\Entity;

class CronJobTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $cronjob = $this->getCronJob();
        $this->assertNull($cronjob->getCommand());

        $cronjob->setCommand('execute.sh');
        $this->assertEquals('execute.sh', $cronjob->getCommand());
    }

    public function testDescription()
    {
        $cronjob = $this->getCronJob();
        $this->assertNull($cronjob->getDescription());

        $cronjob->setDescription('Such a nice description');
        $this->assertEquals('Such a nice description', $cronjob->getDescription());
    }

    public function testInterval()
    {
        $cronjob = $this->getCronJob();
        $this->assertNull($cronjob->getInterval());

        $cronjob->setInterval('PT1S');
        $this->assertEquals('PT1S', $cronjob->getInterval());
    }

    public function testNextRun()
    {
        $cronjob = $this->getCronJob();
        $this->assertNull($cronjob->getNextRun());

        $cronjob->setNextRun(new \DateTime('+4 hours'));
        $this->assertEquals(new \DateTime('+4 hours'), $cronjob->getNextRun());
    }

    public function testEnabled()
    {
        $cronjob = $this->getCronJob();
        $this->assertNull($cronjob->getEnabled());

        $cronjob->setEnabled(true);
        $this->assertEquals(true, $cronjob->getEnabled());
    }

    public function testResults()
    {
        $cronjob = $this->getCronJob();
        $this->assertCount(0, $cronjob->getResults());
    }

    public function testMostRecentRun()
    {
        $cronjob = $this->getCronJob();
        $this->assertNull($cronjob->getMostRecentRun());

        $result = $this->getMockForAbstractClass('Alpixel\Bundle\CronBundle\Entity\CronJobResult');

        $cronjob->setMostRecentRun($result);
        $this->assertEquals($result, $cronjob->getMostRecentRun());
    }

    public function getCronJob()
    {
        return $this->getMockForAbstractClass('Alpixel\Bundle\CronBundle\Entity\CronJob');
    }
}
