<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Destination;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Worker\FetchAvailableJourney;
use CalculatorBundle\Worker\UpdateVoyageWorker;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JourneyContext extends CommonContext
{
    /** @var FetchAvailableJourney */
    private $fetchAvailableJourney;

    /** @var UpdateVoyageWorker */
    private $updateVoyageWorker;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->fetchAvailableJourney = $container->get('fetch_available_journey_worker');
        $this->updateVoyageWorker = $container->get('update_voyages_worker');
    }

    /**
     * @Given les possibilitées de transports :
     */
    public function lesPossibilitéesDeTransports(TableNode $tableAvailableJourney)
    {
        foreach ($tableAvailableJourney as $availableJourneyRow) {
            $availableJourney = new AvailableJourney();
            $availableJourney->setFromDestination($this->findDestinationByName($availableJourneyRow['depuis']))
                ->setToDestination($this->findDestinationByName($availableJourneyRow["jusqu'à"]));

            if ($availableJourneyRow['prix avion']) {
                $availableJourney->setFlyPrices($availableJourneyRow['prix avion'])
                    ->setFlyTime($availableJourneyRow['temps avion']);
            }
            if ($availableJourneyRow['prix bus']) {
                $availableJourney->setBusPrices($availableJourneyRow['prix bus'])
                    ->setBusTime($availableJourneyRow['temps bus']);
            }
            if ($availableJourneyRow['prix train']) {
                $availableJourney->setTrainPrices($availableJourneyRow['prix train'])
                    ->setTrainTime($availableJourneyRow['temps train']);
            }

            $this->em->persist($availableJourney);
        }
        $this->em->flush();
    }

    /**
     * @When je change le mode de transport à :transportType pour le trajet de :fromDestination à :toDestination du voyage :voyageName
     */
    public function jeChangeLeModeDeTransportAPourLeTrajetDeÀDeLUtilisateur($transportType, $fromDestination, $toDestination, $voyageName)
    {
        $voyage = $this->findVoyageByName($voyageName);
        $destination = $this->findDestinationByName($fromDestination);
        $stages = $this->findStageByDestinationAndVoyage($destination, $voyage);

        $stage = $stages[0];
        $stage->setTransportType($transportType);
    }

    /**
     * @Then il existe les transports suivants au voyage :voyageName :
     */
    public function ilExisteLesTransportsSuivantsAuVoyage($voyageName, TableNode $tableJourney)
    {
        $voyage = $this->findVoyageByName($voyageName);

        /** @var Destination $nextDestination */
        $nextDestination = null;
        foreach ($tableJourney as $journeyRow) {
            $fromDestinationName = $journeyRow['depuis'];
            $toDestinationName = $journeyRow["jusqu'à"];
            $transportTypeExpected = $journeyRow['type de transport'];

            $destinationFrom = $this->findDestinationByName($fromDestinationName);

            if (!is_null($nextDestination)) {
                $this->assertEquals($nextDestination->getName(), $fromDestinationName);
            }

            $nextDestination = $this->findDestinationByName($toDestinationName);

            $transportType = null;
            if ($destinationFrom->getId() === $voyage->getStartDestination()->getId()) {
                $transportType = $voyage->getTransportType();
            } else {
                $stages = $this->findStageByDestinationAndVoyage($destinationFrom, $voyage);
                $transportType = $stages[0]->getTransportType();
            }

            $this->assertEquals($transportTypeExpected, $transportType);
        }

    }

    /**
     * @When je lance la récupération des transports possibles
     */
    public function jeLanceLaRécupérationDesTransportsPossibles()
    {
        $this->fetchAvailableJourney->fetchAll();
    }

    /**
     * @Then les possibilitées de transports sont :
     */
    public function lesPossibilitéesDeTransportsSont(TableNode $tableAvailableJourney)
    {
        $availableJourneyRepository = $this->em->getRepository('CalculatorBundle:AvailableJourney');

        foreach ($tableAvailableJourney as $availableJourneyRow) {
            $fromDestination = $this->findDestinationByName($availableJourneyRow['depuis']);
            $toDestination = $this->findDestinationByName($availableJourneyRow["jusqu'à"]);

            /** @var AvailableJourney $availableJourney */
            $availableJourney = $availableJourneyRepository->findOneBy(['fromDestination' => $fromDestination, 'toDestination' => $toDestination]);

            $this->assertNotNull($availableJourney, $fromDestination->getName() . " - " . $toDestination->getName());

            $this->assertEquals($availableJourneyRow['prix avion'], $availableJourney->getFlyPrices(), $fromDestination->getName() . " - " . $toDestination->getName());
            $this->assertEquals($availableJourneyRow['temps avion'], $availableJourney->getFlyTime(), $fromDestination->getName() . " - " . $toDestination->getName());
            $this->assertEquals($availableJourneyRow['prix bus'], $availableJourney->getBusPrices(), $fromDestination->getName() . " - " . $toDestination->getName());
            $this->assertEquals($availableJourneyRow['temps bus'], $availableJourney->getBusTime(), $fromDestination->getName() . " - " . $toDestination->getName());
            $this->assertEquals($availableJourneyRow['prix train'], $availableJourney->getTrainPrices(), $fromDestination->getName() . " - " . $toDestination->getName());
            $this->assertEquals($availableJourneyRow['temps train'], $availableJourney->getTrainTime(), $fromDestination->getName() . " - " . $toDestination->getName());
        }

    }

    /**
     * @When je met à jour les voyages avec les trajets disponibles
     */
    public function jeMetÀJourLesVoyagesAvecLesTrajetsDisponibles()
    {
        $this->updateVoyageWorker->run();
    }
}
