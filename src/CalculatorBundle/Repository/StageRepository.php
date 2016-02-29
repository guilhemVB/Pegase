<?php

namespace CalculatorBundle\Repository;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Entity\Voyage;
use Doctrine\ORM\EntityRepository;

/**
 * @package CalculatorBundle\Repository
 * @method Stage find($id)
 */
class StageRepository extends EntityRepository
{

    /**
     * @param Destination $destination
     * @param Voyage $voyage
     * @return Stage[]
     */
    public function findStagesFromDestinationAndVoyage(Destination $destination, Voyage $voyage)
    {
        $qb = $this->createQueryBuilder('stage')
            ->select('stage')
            ->leftJoin('stage.destination', 'destination')
            ->leftJoin('stage.voyage', 'voyage')
            ->where('destination = :destination')
            ->andWhere('voyage = :voyage')
            ->setParameter('destination', $destination)
            ->setParameter('voyage', $voyage);
        try {
            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param Voyage $voyage
     * @param Stage $currentStage
     * @return Stage|null
     */
    public function findStageBefore(Voyage $voyage, Stage $currentStage)
    {
        $qb = $this->createQueryBuilder('stage')
            ->select('stage')
            ->leftJoin('stage.voyage', 'voyage')
            ->where('stage.position = :position')
            ->andWhere('voyage = :voyage')
            ->setParameter('position', $currentStage->getPosition() - 1)
            ->setParameter('voyage', $voyage);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
