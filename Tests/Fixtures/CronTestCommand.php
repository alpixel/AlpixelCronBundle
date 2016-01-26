<?php

namespace Alpixel\Bundle\CronBundle\Tests\Fixtures;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Alpixel\Bundle\CronBundle\Annotation\CronJob;

/**
 * @CronJob("PT1S")
 */
class CronTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('my_cron_test')->setDescription('Test my new cron job');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        file_put_contents(__DIR__.'/app/cache/cron_result.log', 'ok');
        $output->writeln("This is a test");
    }
}
