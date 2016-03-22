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
     * @param int|null $limit
     * @return Voyage[]
     */
    public function findTypicalVoyages($userId, $limit = null)
    {
        $qb = $this->createQueryBuilder('voyage')
            ->select('voyage')
            ->join('voyage.user', 'user')
            ->join('voyage.stages', 'stages')
            ->where('user.id = :userId')
            ->groupBy('voyage.id')
            ->addGroupBy('voyage.name')
            ->setParameter('userId', $userId);

        if ($limit) {
            $qb->setMaxResults($limit);
            $qb->orderBy('voyage.id', 'DESC');
        }


        return $qb->getQuery()->getResult();
    }
}
