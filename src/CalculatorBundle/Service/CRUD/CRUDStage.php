<?php

namespace CalculatorBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Entity\Voyage;
use CalculatorBundle\Repository\AvailableJourneyRepository;
use CalculatorBundle\Repository\StageRepository;
use CalculatorBundle\Service\Journey\BestJourneyFinder;
use Doctrine\ORM\EntityManager;

class CRUDStage
{

    const TRAIN = "TRAIN";
    const BUS = "BUS";
    const FLY = "FLY";
    const NONE = "NONE";

    /**
     * @var EntityManager
     */
    private $em;

    /** @var StageRepository */
    private $stageRepository;

    /** @var BestJourneyFinder */
    private $bestJourneyFinder;

    public function __construct(EntityManager $em, BestJourneyFinder $bestJourneyFinder)
    {
        $this->em = $em;
        $this->stageRepository = $em->getRepository('CalculatorBundle:Stage');
        $this->bestJourneyFinder = $bestJourneyFinder;
    }


    /**
     * @param Destination $destination
     * @param Voyage $voyage
     * @param int $nbDays
     * @return Stage
     */
    public function add(Destination $destination, Voyage $voyage, $nbDays)
    {
        $stage = new Stage();
        $stage->setDestination($destination);
        $stage->setNbDays($nbDays);
        $stage->setPosition(count($voyage->getStages()) + 1);
        $stage->setVoyage($voyage);
        $this->em->persist($stage);

        $voyage->addStage($stage);
        $this->em->persist($voyage);

        $this->em->flush();

        $this->addJourneyInPreviewStage($voyage, $stage);

        return $stage;
    }

    /**
     * @param Voyage $voyage
     * @param Stage $currentStage
     * @throws \Exception
     */
    private function addJourneyInPreviewStage(Voyage $voyage, Stage $currentStage)
    {
        $stageBefore = $this->stageRepository->findStageBefore($voyage, $currentStage);

        /** @var Voyage|Stage $from */
        $from = null;
        /** @var Destination $fromDestination */
        $fromDestination = null;

        if (is_null($stageBefore)) {
            $fromDestination = $voyage->getStartDestination();
            $from = $voyage;
        } else {
            $fromDestination = $stageBefore->getDestination();
            $from = $stageBefore;
        }


        /** @var AvailableJourneyRepository $availableJourneyRepository */
        $availableJourneyRepository = $this->em->getRepository('CalculatorBundle:AvailableJourney');
        $availableJourney = $availableJourneyRepository->findOneBy(['fromDestination' => $fromDestination, 'toDestination' => $currentStage->getDestination()]);

        if (is_null($availableJourney)) {
            throw new \Exception("Can't find availableJourney with : " . $fromDestination->getName() . " and " . $currentStage->getDestination()->getName());
        }

        $transportType = $this->bestJourneyFinder->chooseBestTransportType($availableJourney);
        $from->setAvailableJourney($availableJourney);
        $from->setTransportType($transportType);

        $this->em->persist($from);
        $this->em->flush();
    }


    /**
     * @param Stage $stage
     */
    public function remove(Stage $stage)
    {
        $voyage = $stage->getVoyage();
        $position = $stage->getPosition();

        /** @var Stage $stageToChange */
        $stageToChange = $this->stageRepository->findOneBy(['voyage' => $voyage, 'position' => $position + 1]);
        while (!is_null($stageToChange)) {
            $stageToChange->setPosition($position);
            $this->em->persist($stageToChange);
            $this->em->flush();
            $position++;
            $stageToChange = $this->stageRepository->findOneBy(['voyage' => $voyage, 'position' => $position + 1]);
        }

        $this->em->remove($stage);
        $this->em->flush();
    }


    /**
     * @param Stage $stage
     * @param int $oldPosition
     * @param int $newPosition
     * @return Stage
     */
    public function changePosition(Stage $stage, $oldPosition, $newPosition)
    {
        $voyage = $stage->getVoyage();
        if ($newPosition < $oldPosition) {
            $itPosition = $newPosition;
            while ($itPosition != $oldPosition) {
                /** @var Stage $stageIt */
                $stageIt = $this->stageRepository->findOneBy(['voyage' => $voyage, 'position' => $itPosition]);
                $itPosition++;
                $stageIt->setPosition($itPosition);
                $this->em->persist($stageIt);
            }
        } elseif ($newPosition > $oldPosition) {
            $itPosition = $newPosition;
            while ($itPosition != $oldPosition) {
                /** @var Stage $stageIt */
                $stageIt = $this->stageRepository->findOneBy(['voyage' => $voyage, 'position' => $itPosition]);
                $itPosition--;
                $stageIt->setPosition($itPosition);
                $this->em->persist($stageIt);
            }
        }

        $stage->setPosition($newPosition);
        $this->em->persist($stage);
        $this->em->flush();

        return $stage;
    }

    /**
     * @param Stage $stage
     * @param float $nbDays
     */
    public function changeNumberDays($stage, $nbDays)
    {
        $stage->setNbDays($nbDays);
        $this->em->persist($stage);
        $this->em->flush();
    }
}
