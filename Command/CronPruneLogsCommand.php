<?php

namespace Alpixel\Bundle\CronBundle\Command;

use Alpixel\Bundle\CronBundle\Entity\CronJobResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Alpixel\Bundle\CronBundle\Entity\CronJob;

class CronPruneLogsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cron:pruneLogs')
             ->setDescription('Prunes the logs for each cron job, leaving only recent failures and the most recent success')
             ->addArgument('job', InputArgument::OPTIONAL, 'Operate only on this job');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $job = $input->getArgument('job');

        if ($job) {
            $output->writeln("Pruning logs for cron job $job");
        } else {
            $output->writeln('Pruning logs for all cron jobs');
        }

        if ($job) {
            $jobObj = $em->getRepository(CronJob::class)->findOneByCommand($job);
            if (!$jobObj) {
                $output->writeln("Couldn't find a job by the name of ".$job);

                return CronJobResult::FAILED;
            }

            $em->getRepository(CronJobResult::class)->deleteOldLogs($jobObj);
        } else {
            $em->getRepository(CronJobResult::class)->deleteOldLogs();
        }

        // Flush the EM
        $em->flush();

        $output->writeln('Logs pruned successfully');

        return CronJobResult::SUCCEEDED;
    }
}
