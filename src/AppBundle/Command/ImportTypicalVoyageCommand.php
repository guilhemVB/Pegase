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

class ImportTypicalVoyageCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:import:typicalVoyage')
            ->setDescription("Permet d'importer et de mettre à jour la liste des voyages types")
            ->addArgument('fileName', InputArgument::REQUIRED, 'Nom du fichier csv à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->import($input, $output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    private function import(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var VoyageRepository $voyageRepository */
        $voyageRepository = $em->getRepository('AppBundle:Voyage');

        /** @var TypicalVoyageRepository $typicalVoyageRepository */
        $typicalVoyageRepository = $em->getRepository('AppBundle:TypicalVoyage');

        /** @var DestinationRepository $destinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $fileName = $input->getArgument('fileName');
        $dataTypicalVoyages = CSVParser::extract($fileName, $input, $output);
        $nbTypicalVoyages = count($dataTypicalVoyages);
        $output->writeln("<info>--- $nbTypicalVoyages voyages types ont été trouvés dans le fichier ---</info>");

        $nbToFlush = 0;
        foreach ($dataTypicalVoyages as $dataTypicalVoyage) {
            $name = $dataTypicalVoyage['nom'];
            $token = $dataTypicalVoyage['token'];
            $startDate = $dataTypicalVoyage['date de départ'];
            $startPlaceName = $dataTypicalVoyage['lieu de départ'];
            $stepsWithNbDays = $dataTypicalVoyage['étapes'];

            $voyage = $voyageRepository->findOneByToken($token);

            if (is_null($voyage)) {
                $output->writeln("<info>Le voyage avec le token '$token' a été créé.</info>");

                $voyage = new Voyage();
                $typicalVoyage = new TypicalVoyage();
                $typicalVoyage->setVoyage($voyage);

                $em->persist($typicalVoyage);
            } else {
                $typicalVoyage = $typicalVoyageRepository->findOneByVoyage($voyage);

                if (is_null($typicalVoyage)) {
                    $output->writeln("<error>Aucun voyage type de trouvé pour le voyage '$name'.</error>");

                    continue;
                }
            }

            $startDestination = $destinationRepository->findOneByName($startPlaceName);
            if (is_null($startDestination)) {
                $output->writeln("<error>Le lieu de départ '$startPlaceName' du voyage '$name' n'a pas été trouvé.</error>");
                continue;
            }

            $voyage->setStartDestination($startDestination);

            try {
                $date = new \DateTime($startDate);
            } catch (\Exception $e) {
                $output->writeln("<error>Impossible de créer la date '$startDate' du voyage '$name'.</error>");
                continue;
            }

            $voyage->setStartDate($date);
            $voyage->setName($name);
            $voyage->setToken($token);
            $voyage->setShowPricesInPublic(true);

            $destinationsNameWithNbDays = explode('-', $stepsWithNbDays);

            if (count($destinationsNameWithNbDays) <= 0) {
                $output->writeln("<error>Pas d'étpes de trouvés pour le voyage '$name'.</error>");
                continue;
            }

            $this->removeStage($voyage);

            $position = 0;
            foreach ($destinationsNameWithNbDays as $destinationNameWithNbDays) {
                $destinationNameAndNbDays = explode(',', $destinationNameWithNbDays);

                if (count($destinationNameAndNbDays) <= 1) {
                    $output->writeln("<error>Le voyage '$name' a l'étape '$destinationNameWithNbDays' qui est mal formé.</error>");
                    continue;
                }

                $destination = $destinationRepository->findOneByName(trim($destinationNameAndNbDays[0]));
                $nbDays = trim($destinationNameAndNbDays[1]);

                if (is_null($destination)) {
                    $output->writeln("<error>Impossible de trouver la destination '" . $destinationNameAndNbDays[0] . "' du voyage '$name' a l'étape '$destinationNameWithNbDays'.</error>");
                    continue;
                }

                $stage = new Stage();
                $stage->setVoyage($voyage);
                $stage->setDestination($destination);
                $stage->setNbDays($nbDays);
                $stage->setPosition($position);
                $position++;

                $voyage->addStage($stage);

                $em->persist($stage);
            }

            $em->persist($voyage);

            $nbToFlush++;
            if ($nbToFlush % 50 == 0) {
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }

    /**
     * @param Voyage $voyage
     */
    private function removeStage($voyage)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $stages = $voyage->getStages();
        foreach ($stages as $stage) {
            $em->remove($stage);
        }
    }

}
