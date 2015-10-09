<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
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
}
