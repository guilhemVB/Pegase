<?php

namespace CalculatorBundle\Repository;

use CalculatorBundle\Entity\Voyage;
use Doctrine\ORM\EntityRepository;

/**
 * @package CalculatorBundle\Repository
 * @method Voyage findOneByName(string)
 * @method Voyage findOneByToken(string)
 */
class VoyageRepository extends EntityRepository
{
    /**
     * @param string $userId
     * @return Voyage[]
     */
    public function findTypicalVoyages($userId)
    {
        $qb = $this->createQueryBuilder('voyage')
            ->select('voyage')
            ->join('voyage.user', 'user')
            ->join('voyage.stages', 'stages')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }
}
