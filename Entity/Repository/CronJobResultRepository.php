<?php

namespace Alpixel\Bundle\CronBundle\Entity\Repository;

use Alpixel\Bundle\CronBundle\Entity\CronJob;
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

    public function findLastRunForCronJob(CronJob $job)
    {
        return $this->createQueryBuilder('cjr')
                    ->select('MAX(cjr.runAt)')
                    ->andWhere('cjr.job = :job')
                    ->andWhere('cjr.result = :success')
                    ->setParameter('job', $job)
                    ->setParameter('success', CronJobResult::SUCCEEDED)
                    ->getQuery()
                    ->getSingleScalarResult();
    }
}
