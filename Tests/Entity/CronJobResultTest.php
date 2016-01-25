<?php

namespace Alpixel\Bundle\CronBundle\Tests\Entity;

use Alpixel\Bundle\CronBundle\Entity\CronJobResult;
use Alpixel\Bundle\CronBundle\Tests\TestCronJob;

class CronJobResultTest extends \PHPUnit_Framework_TestCase
{
    public function testRunAt()
    {
        $result = $this->getCronJobResult();
        $this->assertNotNull($result->getRunAt());

        $result->setRunAt(new \DateTime('1987-04-16'));
        $this->assertEquals(new \DateTime('1987-04-16'), $result->getRunAt());
    }

    public function testRunTime()
    {
        $result = $this->getCronJobResult();
        $this->assertNull($result->getRunTime());

        $result->setRunTime(2.54);
        $this->assertEquals(2.54, $result->getRunTime());
    }

    public function testResult()
    {
        $result = $this->getCronJobResult();
        $this->assertNull($result->getResult());

        $result->setResult(CronJobResult::SUCCEEDED);
        $this->assertEquals(CronJobResult::SUCCEEDED, $result->getResult());
    }

    public function testOutput()
    {
        $result = $this->getCronJobResult();
        $this->assertNull($result->getOutput());

        $result->setOutput('success');
        $this->assertEquals('success', $result->getOutput());
    }

    public function testJob()
    {
        $result = $this->getCronJobResult();
        $this->assertNull($result->getJob());

        $job = $this->getMockForAbstractClass('Alpixel\Bundle\CronBundle\Entity\CronJob');

        $result->setJob($job);
        $this->assertEquals($job, $result->getJob());
    }

    public function getCronJobResult()
    {
        return $this->getMockForAbstractClass('Alpixel\Bundle\CronBundle\Entity\CronJobResult');
    }

}
