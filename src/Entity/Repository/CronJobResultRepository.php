<?php
namespace Alpixel\Bundle\CronBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Alpixel\Bundle\CronBundle\Entity\CronJobResult;

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
