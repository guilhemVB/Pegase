<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Country;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Entity
 * @method Country findOneByName(string)
 */
class CountryRepository extends EntityRepository
{
}
