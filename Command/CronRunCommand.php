<?php

namespace Alpixel\Bundle\CronBundle\Command;

use Alpixel\Bundle\CronBundle\Entity\CronJob;
use Alpixel\Bundle\CronBundle\Entity\CronJobResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronRunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cron:run')
             ->setDescription('Runs any currently schedule cron jobs')
             ->addArgument('job', InputArgument::OPTIONAL, 'Run only this job (if enabled)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $jobRepo = $entityManager->getRepository(CronJob::class);

        $jobsToRun = [];
        if ($jobName = $input->getArgument('job')) {
            try {
                $jobObj = $jobRepo->findOneByCommand($jobName);
                if ($jobObj->getEnabled()) {
                    $jobsToRun = [$jobObj];
                }
            } catch (\Exception $e) {
                $output->writeln("Couldn't find a job by the name of $jobName");

                return CronJobResult::FAILED;
            }
        } else {
            $jobsToRun = $jobRepo->findDueTasks();
        }

        $jobCount = count($jobsToRun);
        $output->writeln("Running $jobCount jobs:");

        foreach ($jobsToRun as $job) {
            $this->runJob($job, $output);
        }

        $entityManager->flush();

        $end = microtime(true);
        $duration = sprintf('%0.2f', $end - $start);
        $output->writeln("Cron run completed in $duration seconds");
    }

    protected function runJob(CronJob $job, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $output->write('Running '.$job->getCommand().': ');

        try {
            $commandToRun = $this->getApplication()->get($job->getCommand());
        } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $ex) {
            $output->writeln(' skipped (command no longer exists)');
            $this->recordJobResult($entityManager, $job, 0, 'Command no longer exists', CronJobResult::SKIPPED);

            // No need to reschedule non-existant commands
            return;
        }

        $emptyInput = new ArgvInput();
        $jobOutput = new MemoryWriter();

        $jobStart = microtime(true);
        try {
            $returnCode = $commandToRun->execute($emptyInput, $jobOutput);
        } catch (\Exception $ex) {
            $returnCode = CronJobResult::FAILED;
            $jobOutput->writeln('');
            $jobOutput->writeln('Job execution failed with exception '.get_class($ex).':');
            $jobOutput->writeln($ex->__toString());
        }
        $jobEnd = microtime(true);

        // Clamp the result to accepted values
        if ($returnCode < CronJobResult::RESULT_MIN || $returnCode > CronJobResult::RESULT_MAX) {
            $returnCode = CronJobResult::FAILED;
        }

        // Output the result
        $statusStr = 'unknown';
        if ($returnCode == CronJobResult::SKIPPED) {
            $statusStr = 'skipped';
        } elseif ($returnCode == CronJobResult::SUCCEEDED) {
            $statusStr = 'succeeded';
        } elseif ($returnCode == CronJobResult::FAILED) {
            $statusStr = 'failed';
        }

        $durationStr = sprintf('%0.2f', $jobEnd - $jobStart);
        $output->writeln("$statusStr in $durationStr seconds");

        // Record the result
        $this->recordJobResult($job, $jobEnd - $jobStart, $jobOutput->getOutput(), $returnCode);

        // And update the job with it's next scheduled time
        $interval = new \DateInterval($job->getInterval());
        $newTime = clone $job->getNextRun();
        $newTime->add($interval);
        $job->setNextRun($newTime);
    }

    protected function recordJobResult(CronJob $job, $timeTaken, $output, $resultCode)
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Create a new CronJobResult
        $result = new CronJobResult();
        $result->setJob($job);
        $result->setRunTime($timeTaken);
        $result->setOutput($output);
        $result->setResult($resultCode);

        // Then update associations and persist it
        $job->setMostRecentRun($result);
        $entityManager->persist($result);
    }
}
