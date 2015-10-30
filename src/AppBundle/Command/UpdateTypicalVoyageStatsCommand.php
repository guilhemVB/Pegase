<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\TypicalVoyage;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Repository\TypicalVoyageRepository;
use AppBundle\Repository\VoyageRepository;
use AppBundle\Service\CSVParser;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use AppBundle\Service\Stats\VoyageStats;
use AppBundle\Service\Tools\DestinationPeriods;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTypicalVoyageStatsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('typicalVoyage:calculate:stats')
            ->setDescription("Permet de calculer les stats des voyages types");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var TypicalVoyageRepository $typicalVoyageRepository */
        $typicalVoyageRepository = $em->getRepository('AppBundle:TypicalVoyage');

        $typicalVoyages = $typicalVoyageRepository->findAllTypicalVoyages();

        foreach ($typicalVoyages as $typicalVoyage) {
            $output->writeln('<info>Update du voyage type "' . $typicalVoyage->getVoyage()->getName() . '"</info>');
            $this->updateTypicalVoyage($typicalVoyage);
            $em->persist($typicalVoyage);
        }

        $em->flush();

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * @param TypicalVoyage $typicalVoyage
     */
    private function updateTypicalVoyage(TypicalVoyage $typicalVoyage)
    {
        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->getContainer()->get('voyage_stats');
        $voyage = $typicalVoyage->getVoyage();

        $stats = $voyageStats->calculate($voyage->getStages(), [
            new StatCalculatorNumberDays(),
            new StatCalculatorPrices(),
        ]);

        $typicalVoyage->setNbDays($stats['nbDays']);
        $typicalVoyage->setPrice($stats['totalCost']);
    }
}
