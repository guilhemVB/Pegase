<?php

namespace CalculatorBundle\Command;

use CalculatorBundle\Worker\FetchAvailableJourney;
use CalculatorBundle\Worker\UpdateVoyageWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use Psr\Log\LoggerInterface;

class FetchAvailableJourneyCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:journey')
            ->setDescription("Permet de récupérer les voyages possibles entre chaque destinations");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LoggerInterface $logger*/
        $logger = $this->getContainer()->get('logger');

        $lock = new LockHandler('app:journey');
        if (!$lock->lock()) {
            $logger->error("The command 'app:journey' is already running in another process. Can't launch it twice at same time.");
            $output->writeln('The command is already running in another process.');
            return ;
        }

        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        /** @var FetchAvailableJourney $fetchAvailableJourney */
        $fetchAvailableJourney = $this->getContainer()->get('fetch_available_journey_worker');
        $fetchAvailableJourney->fetch();

        $lock->release();

        /** @var UpdateVoyageWorker $updateVoyageWorker */
        $updateVoyageWorker = $this->getContainer()->get('update_voyages_worker');
        $updateVoyageWorker->run();

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

}
