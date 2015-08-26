<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Destination;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 * @method Destination findOneByName(string)
 */
class DestinationRepository extends EntityRepository
{
}
