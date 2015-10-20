<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Voyage;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 * @method Voyage findOneByName(string)
 * @method Voyage findOneByToken(string)
 */
class VoyageRepository extends EntityRepository
{
}
