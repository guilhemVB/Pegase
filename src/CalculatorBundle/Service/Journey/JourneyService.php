<?php

namespace CalculatorBundle\Service\Journey;

use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Entity\Voyage;
use CalculatorBundle\Repository\AvailableJourneyRepository;
use CalculatorBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;

class JourneyService
{

    /**@var EntityManager */
    private $em;

    /** @var StageRepository */
    private $stageRepository;

    /** @var BestJourneyFinder */
    private $bestJourneyFinder;

    /** @var AvailableJourneyRepository */
    private $availableJourneyRepository;

    public function __construct(EntityManager $em, BestJourneyFinder $bestJourneyFinder)
    {
        $this->em = $em;
        $this->bestJourneyFinder = $bestJourneyFinder;

        $this->stageRepository = $em->getRepository('CalculatorBundle:Stage');
        $this->availableJourneyRepository = $em->getRepository('CalculatorBundle:AvailableJourney');
    }

    /**
     * @param Stage $stage
     * @return Stage
     */
    public function updateJourneyByStage(Stage $stage)
    {
        $voyage = $stage->getVoyage();
        $stageAfter = $this->stageRepository->findStageByPosition($voyage, $stage->getPosition() + 1);

        if (is_null($stageAfter)) {
            $stage->setAvailableJourney(null);
            $stage->setTransportType(null);
        } else {
            /** @var AvailableJourney $availableJourney */
            $availableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $stage->getDestination(), 'toDestination' => $stageAfter->getDestination()]);

            if (is_null($availableJourney)) {
                $stage->setAvailableJourney(null);
                $stage->setTransportType(null);
            } else {
                $transportType = $this->bestJourneyFinder->chooseBestTransportType($availableJourney);

                $stage->setAvailableJourney($availableJourney);
                $stage->setTransportType($transportType);
            }
        }

        $this->em->persist($stage);
        $this->em->flush();

        return $stage;
    }

    /**
     * @param Voyage $voyage
     * @return Voyage
     */
    public function updateJourneyByVoyage(Voyage $voyage)
    {
        $stageAfter = $this->stageRepository->findStageByPosition($voyage, 1);

        if (is_null($stageAfter)) {
            $voyage->setAvailableJourney(null);
            $voyage->setTransportType(null);
        } else {
            /** @var AvailableJourney $availableJourney */
            $availableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $voyage->getStartDestination(), 'toDestination' => $stageAfter->getDestination()]);

            if (is_null($availableJourney)) {
                $voyage->setAvailableJourney(null);
                $voyage->setTransportType(null);
            } else {
                $transportType = $this->bestJourneyFinder->chooseBestTransportType($availableJourney);

                $voyage->setAvailableJourney($availableJourney);
                $voyage->setTransportType($transportType);
            }
        }

        $this->em->persist($voyage);
        $this->em->flush();

        return $voyage;
    }

}