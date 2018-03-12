<?php

namespace AppBundle\Command;


use AppBundle\Entity\CronjobSchedule;
use Cron\CronExpression;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateSchedulesCommand extends ContainerAwareCommand
{
    const DEFAULT_MAX_SCHEDULE = 5;
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('scheduler:update-schedules')
            ->setDescription('Adds additional jobs schedules. Should be run every minute for best result')
            ->addOption(
                'count',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Number of schedules to add for each cronjob',
                self::DEFAULT_MAX_SCHEDULE
            );
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $maxScheduledJobs = (int)$input->getOption('count');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $allCronJobs = $em->getRepository('AppBundle:Cronjob')->findAll();
        foreach ($allCronJobs as $cronjob) {
            $scheduledJobsCount = $em->getRepository('AppBundle:CronjobSchedule')->getNextRunsCountFor($cronjob);
            if ($scheduledJobsCount < $maxScheduledJobs) {
                $diff = $maxScheduledJobs - $scheduledJobsCount;
                // Add additional scheduled launchs
                $cronExp = CronExpression::factory($cronjob->getCronExpression());
                $cronSchedules = [];
                for ($i = $scheduledJobsCount; $i < $diff; $i++) {
                    $schedule = new CronjobSchedule();
                    $schedule
                        ->setCronjob($cronjob)
                        ->setScheduledAt($cronExp->getNextRunDate('now', $i));
                    $cronjob->addSchedule($schedule);
                    $cronSchedules[] = $schedule;
                }
                foreach ($cronSchedules as $cronSchedule) {
                    $em->persist($cronSchedule);
                }
                $em->flush();
            } else {
                echo 'Nothing to do' . PHP_EOL;
            }
        }
    }
}

