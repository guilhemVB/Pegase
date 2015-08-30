<?php

namespace AppBundle\Repository;

use AppBundle\Entity\BagItem;
use Doctrine\ORM\EntityRepository;

/**
 * @package AppBundle\Repository
 * @method BagItem findOneByName(string)
 */
class BagItemRepository extends EntityRepository
{
}
