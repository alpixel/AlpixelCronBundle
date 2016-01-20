<?php

namespace Alpixel\Bundle\CronBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CronJobRepository extends EntityRepository
{
    public function getKnownJobs()
    {
        $data = $this->getEntityManager()
                     ->createQuery('SELECT job.command FROM CronBundle:CronJob job')
                     ->getScalarResult();
        $toRet = [];
        foreach ($data as $datum) {
            $toRet[] = $datum['command'];
        }

        return $toRet;
    }

    public function findDueTasks()
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT job FROM CronBundle:CronJob job
                                              WHERE job.nextRun <= :curTime
                                              AND job.enabled = 1')
                    ->setParameter('curTime', new \DateTime())
                    ->getResult();
    }
}
