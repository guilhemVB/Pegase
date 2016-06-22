<?php

namespace CalculatorBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Repository\AvailableJourneyRepository;
use CalculatorBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;

class CRUDAvailableJourney
{

    /**
     * @var EntityManager
     */
    private $em;

    /** @var AvailableJourneyRepository */
    private $availableJourneyRepository;

    /** @var StageRepository */
    private $stageRepository;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->availableJourneyRepository = $em->getRepository('CalculatorBundle:AvailableJourney');
        $this->stageRepository = $em->getRepository('CalculatorBundle:Stage');
    }

    /**
     * @param Destination $destination
     * @return AvailableJourney[]
     */
    public function findAvailableJourneysByDestination(Destination $destination)
    {
        $availableJourneys = $this->availableJourneyRepository->findBy(['fromDestination' => $destination]);

        return array_merge($availableJourneys, $this->availableJourneyRepository->findBy(['toDestination' => $destination]));
    }

    /**
     * @param Destination $destination
     * @return int
     */
    public function removeAvailableJourneyByDestination(Destination $destination)
    {
        $availableJourneys = $this->findAvailableJourneysByDestination($destination);

        foreach ($availableJourneys as $availableJourney) {
            $this->remove($availableJourney);
        }

        return count($availableJourneys);
    }

    /**
     * @param AvailableJourney $availableJourney
     */
    public function remove(AvailableJourney $availableJourney)
    {
        /** @var Stage[] $stagesWithThisAvailableJourney */
        $stagesWithThisAvailableJourney = $this->stageRepository->findBy(['availableJourney' => $availableJourney]);

        foreach ($stagesWithThisAvailableJourney as $stageWithThisAvailableJourney) {
            $stageWithThisAvailableJourney->setAvailableJourney(null);
            $stageWithThisAvailableJourney->setTransportType(null);
            $this->em->persist($stageWithThisAvailableJourney);
        }
        $this->em->flush();

        $this->em->remove($availableJourney);
        $this->em->flush();
    }

}
