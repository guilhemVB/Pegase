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

    /**
     * @return Country[]
     */
    public function findCountriesWithDestinations()
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.destinations', 'd')
            ->addSelect('d')
            ->orderBy('c.name')
            ->addGroupBy('d.name');

        return $qb->getQuery()->getResult();
    }
}
