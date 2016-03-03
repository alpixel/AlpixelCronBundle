<?php

namespace Alpixel\Bundle\CronBundle\Annotation;

/**
 * @Annotation()
 * @Target("CLASS")
 */
use Doctrine\Common\Annotations\Annotation;

final class CronJob extends Annotation
{
    public $value;
    public $startTime;
}
