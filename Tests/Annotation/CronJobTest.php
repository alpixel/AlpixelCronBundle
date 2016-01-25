<?php

namespace Alpixel\Bundle\CronBundle\Tests\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

class CronJobTest extends \PHPUnit_Framework_TestCase
{
    public function testValidTarget()
    {
        $target = new Target(['value' => ['CLASS']]);
        $this->assertEquals(Target::TARGET_CLASS, $target->targets);
    }
}
