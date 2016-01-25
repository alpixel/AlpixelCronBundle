<?php

namespace Alpixel\Bundle\CronBundle\Tests\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

class CronJobTests extends \PHPUnit_Framework_TestCase
{
    public function testValidTarget()
    {
        $target = new Target(array("value" => array("CLASS")));
        $this->assertEquals(Target::TARGET_CLASS, $target->targets);
    }
}
