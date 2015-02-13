<?php
namespace Alpixel\Bundle\CronBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cronjob")
 * @ORM\Entity(repositoryClass="Alpixel\Bundle\CronBundle\Entity\Repository\CronJobRepository")
 */
class CronJob
{
    /**
     * @ORM\Id
     * @ORM\Column(name="cron_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column
     * @var string $command
     */
    protected $command;
    /**
     * @ORM\Column
     * @var string $description
     */
    protected $description;

    /**
     * @ORM\Column(name="job_interval", type="string", length=40)
     * @var string $interval
     */
    protected $interval;
    /**
     * @ORM\Column(type="datetime")
     * @var DateTime $nextRun
     */
    protected $nextRun;
    /**
     * @ORM\Column(type="boolean")
     * @var boolean $enabled
     */
    protected $enabled;

    /**
     * @ORM\OneToMany(targetEntity="CronJobResult", mappedBy="job")
     * @var ArrayCollection
     */
    protected $results;

    /**
     * @ORM\OneToOne(targetEntity="CronJobResult")
     * @ORM\JoinColumn(name="cron_result_id",referencedColumnName="cron_result_id", nullable=true, onDelete="SET NULL")
     * @var CronJobResult
     */
    protected $mostRecentRun;


    public function __construct()
    {
        $this->results = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set command
     *
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * Get command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set interval
     *
     * @param string $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * Get interval
     *
     * @return string
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set nextRun
     *
     * @param datetime $nextRun
     */
    public function setNextRun($nextRun)
    {
        $this->nextRun = $nextRun;
    }

    /**
     * Get nextRun
     *
     * @return datetime
     */
    public function getNextRun()
    {
        return $this->nextRun;
    }

    /**
     * Add results
     *
     * @param Alpixel\Bundle\CronBundle\Entity\CronJobResult $results
     */
    public function addCronJobResult(\Alpixel\Bundle\CronBundle\Entity\CronJobResult $results)
    {
        $this->results[] = $results;
    }

    /**
     * Get results
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set mostRecentRun
     *
     * @param Alpixel\Bundle\CronBundle\Entity\CronJobResult $mostRecentRun
     */
    public function setMostRecentRun(\Alpixel\Bundle\CronBundle\Entity\CronJobResult $mostRecentRun)
    {
        $this->mostRecentRun = $mostRecentRun;
    }

    /**
     * Get mostRecentRun
     *
     * @return Alpixel\Bundle\CronBundle\Entity\CronJobResult
     */
    public function getMostRecentRun()
    {
        return $this->mostRecentRun;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
