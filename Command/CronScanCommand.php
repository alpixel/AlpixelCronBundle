<?php

namespace Alpixel\Bundle\CronBundle\Command;

use Alpixel\Bundle\CronBundle\Annotation\CronJob as CronJobAnno;
use Alpixel\Bundle\CronBundle\Entity\CronJob;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Alpixel\Bundle\CronBundle\Entity\CronJobResult;

class CronScanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cron:scan')
             ->setDescription('Scans for any new or deleted cron jobs')
             ->addOption('keep-deleted', 'k', InputOption::VALUE_NONE, 'If set, deleted cron jobs will not be removed')
             ->addOption('default-disabled', 'd', InputOption::VALUE_NONE, 'If set, new jobs will be disabled by default');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keepDeleted = $input->getOption('keep-deleted');
        $defaultDisabled = $input->getOption('default-disabled');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Enumerate the known jobs
        $jobRepo = $em->getRepository(CronJob::class);
        $knownJobs = $jobRepo->getKnownJobs();
        $knownJobs = array_fill_keys($knownJobs, true);

        // Enumerate all the jobs currently loaded
        $reader = $this->getContainer()->get('annotation_reader');

        foreach ($this->getApplication()->all() as $command) {
            // Check for an @CronJob annotation
            $reflClass = new \ReflectionClass($command);
            foreach ($reader->getClassAnnotations($reflClass) as $anno) {
                if ($anno instanceof CronJobAnno) {
                    $job = $command->getName();
                    if (array_key_exists($job, $knownJobs)) {
                        // Clear it from the known jobs so that we don't try to delete it
                        unset($knownJobs[$job]);

                        // Update the job if necessary
                        $currentJob = $jobRepo->findOneByCommand($job);
                        $currentJob->setDescription($command->getDescription());
                        if ($currentJob->getInterval() != $anno->value) {
                            $newTime = new \DateTime();
                            $newTime = $newTime->add(new \DateInterval($anno->value));

                            $currentJob->setInterval($anno->value);
                            $currentJob->setNextRun($newTime);
                            $output->writeln("Updated interval for $job to {$anno->value}");
                        }
                    } else {
                        $this->newJobFound($output, $command, $anno, $defaultDisabled);
                    }
                }
            }
        }

        // Clear any jobs that weren't found
        if (!$keepDeleted) {
            foreach (array_keys($knownJobs) as $deletedJob) {
                $output->writeln("Deleting job: $deletedJob");
                $jobToDelete = $jobRepo->findOneByCommand($deletedJob);
                $em->remove($jobToDelete);
            }
        }

        $em->flush();
        $output->writeln('Finished scanning for cron jobs');
    }

    protected function newJobFound(OutputInterface $output, Command $command, CronJobAnno $anno, $defaultDisabled = false)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $newJob = new CronJob();
        $newJob->setCommand($command->getName());
        $newJob->setDescription($command->getDescription());
        $newJob->setInterval($anno->value);

        if ($anno->startTime === null) {
            $newJob->setNextRun(new \DateTime());
        } else {
            $newJob->setNextRun(new \DateTime($anno->startTime));
        }

        $newJob->setEnabled(!$defaultDisabled);

        $output->writeln('Added the job '.$newJob->getCommand().' with interval '.$newJob->getInterval());
        $em->persist($newJob);
    }
}
