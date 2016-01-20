<?php

namespace Alpixel\Bundle\CronBundle\Entity\Repository;

use Alpixel\Bundle\CronBundle\Entity\CronJobResult;
use Doctrine\ORM\EntityRepository;

class CronJobResultRepository extends EntityRepository
{
    public function deleteOldLogs(CronJob $job = null)
    {
        $this
            ->getEntityManager()
            ->createQuery('DELETE CronBundle:CronJobResult result')
            ->getResult();
    }
}
