<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Destination;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 * @method Destination findOneByName(string)
 * @method Destination find($id)
 */
class DestinationRepository extends EntityRepository
{

    /**
     * @param int $nbDestination
     * @return Destination[]
     */
    public function findLastCompleteDestinations($nbDestination = 3)
    {
        return $this->findBy(['isPartial' => false], ['createdAt' => 'DESC'], $nbDestination);
    }
}
