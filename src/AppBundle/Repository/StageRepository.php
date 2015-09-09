<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 */
class StageRepository extends EntityRepository
{

    /**
     * @param Destination $destination
     * @param Voyage $voyage
     * @return Stage|null
     */
    public function findOneStageFromDestinationAndVoyage(Destination $destination, Voyage $voyage)
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
            return $qb->getQuery()->getSingleResult();
        } catch (\Exception $e) {
            return null;
        }
    }
}
