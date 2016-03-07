<?php

namespace Alpixel\Bundle\CronBundle\Entity\Repository;

use Alpixel\Bundle\CronBundle\Entity\CronJob;
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

    public function findLastRunForCronJob(CronJob $job)
    {
        return $this->createQueryBuilder('cjr')
                    ->select('MAX(cjr.runAt)')
                    ->andWhere('cjr.job = :job')
                    ->andWhere('cjr.result = true')
                    ->setParameter('job', $job)
                    ->getQuery()
                    ->getSingleScalarResult();
    }
}
