<?php

namespace AppBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;

class CRUDStage
{

    /**
     * @var EntityManager
     */
    private $em;

    /** @var StageRepository */
    private $stageRepository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->stageRepository = $em->getRepository('AppBundle:Stage');
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

        return $stage;
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
        while(!is_null($stageToChange)) {
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
