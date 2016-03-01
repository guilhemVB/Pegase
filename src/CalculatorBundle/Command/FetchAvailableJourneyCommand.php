<?php

namespace CalculatorBundle\Command;

use CalculatorBundle\Entity\TypicalVoyage;
use CalculatorBundle\Repository\TypicalVoyageRepository;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use AppBundle\Service\Stats\VoyageStats;
use CalculatorBundle\Worker\FetchAvailableJourney;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        /** @var FetchAvailableJourney $fetchAvailableJourney */
        $fetchAvailableJourney = $this->getContainer()->get('fetch_available_journey_worker');

        $fetchAvailableJourney->fetchAll();

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

}
