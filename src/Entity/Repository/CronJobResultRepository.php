<?php
namespace Alpixel\Component\CronBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Alpixel\Component\CronBundle\Entity\CronJobResult;

class CronJobResultRepository extends EntityRepository
{
    public function deleteOldLogs(CronJob $job = null)
    {
        $this
            ->getEntityManager()
            ->createQuery("DELETE CronBundle:CronJobResult result")
            ->getResult()
         ;
    }
}
