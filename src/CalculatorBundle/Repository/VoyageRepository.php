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
}
