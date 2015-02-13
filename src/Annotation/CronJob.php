<?php

namespace Alpixel\Component\CronBundle\Annotation;

/**
 * @Annotation()
 * @Target("CLASS")
 */
use Doctrine\Common\Annotations\Annotation;


class CronJob extends Annotation
{
    public $value;
}
