<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Destination;
use AppKernel;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Entity\Journey;
use CalculatorBundle\Repository\JourneyRepository;
use CalculatorBundle\Repository\StageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JourneyContext extends CommonContext
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
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
                    ->setFlyTime(\DateTime::createFromFormat("G:i", $availableJourneyRow['temps avion']));
            }
            if ($availableJourneyRow['prix bus']) {
                $availableJourney->setBusPrices($availableJourneyRow['prix bus'])
                    ->setBusTime(\DateTime::createFromFormat("G:i", $availableJourneyRow['temps bus']));
            }
            if ($availableJourneyRow['prix train']) {
                $availableJourney->setTrainPrices($availableJourneyRow['prix train'])
                    ->setTrainTime(\DateTime::createFromFormat("G:i", $availableJourneyRow['temps train']));
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
}
