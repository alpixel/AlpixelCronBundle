<?php

namespace Alpixel\Bundle\CronBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cronjob_result")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\CronBundle\Entity\Repository\CronJobResultRepository")
 */
class CronJobResult
{
    const RESULT_MIN = 0;
    const SUCCEEDED = 0;
    const FAILED = 1;
    const SKIPPED = 2;
    const RESULT_MAX = 2;

    /**
     * @ORM\Id
     * @ORM\Column(name="cron_result_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $runAt;
    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    protected $runTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $result;
    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $output;

    /**
     * @ORM\ManyToOne(targetEntity="CronJob", inversedBy="results", cascade={"persist"})
     * @ORM\JoinColumn(name="cron_id",referencedColumnName="cron_id", nullable=false, onDelete="CASCADE")
     *
     * @var CronJob
     */
    protected $job;

    public function __construct()
    {
        $this->runAt = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set runAt.
     *
     * @param datetime $runAt
     */
    public function setRunAt($runAt)
    {
        $this->runAt = $runAt;
    }

    /**
     * Get runAt.
     *
     * @return datetime
     */
    public function getRunAt()
    {
        return $this->runAt;
    }

    /**
     * Set runTime.
     *
     * @param float $runTime
     */
    public function setRunTime($runTime)
    {
        $this->runTime = $runTime;
    }

    /**
     * Get runTime.
     *
     * @return float
     */
    public function getRunTime()
    {
        return $this->runTime;
    }

    /**
     * Set result.
     *
     * @param int $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Get result.
     *
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set output.
     *
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * Get output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set job.
     *
     * @param Alpixel\Bundle\CronBundle\Entity\CronJob $job
     */
    public function setJob(\Alpixel\Bundle\CronBundle\Entity\CronJob $job)
    {
        $this->job = $job;
    }

    /**
     * Get job.
     *
     * @return Alpixel\Bundle\CronBundle\Entity\CronJob
     */
    public function getJob()
    {
        return $this->job;
    }
}
