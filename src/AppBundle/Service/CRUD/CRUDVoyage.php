<?php

namespace AppBundle\Service\CRUD;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Traveller;
use AppBundle\Entity\User;
use AppBundle\Entity\Voyage;
use Doctrine\ORM\EntityManager;

class CRUDVoyage
{

    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param User $user
     * @param string $name
     * @param string $deparatureDate
     * @param Destination $destination
     * @return Voyage
     */
    public function add(User $user, $name, $deparatureDate, $destination)
    {
        $voyage = new Voyage();
        $voyage->setUser($user);
        $voyage->setName($name);
        $voyage->setStartDate(new \DateTime($deparatureDate));
        $voyage->setStartDestination($destination);

        $this->em->persist($voyage);
        $this->em->flush();

        return $voyage;
    }


    /**
     * @param Voyage $voyage
     * @param string $name
     * @param string $deparatureDate
     * @param Destination $destination
     * @return Voyage
     */
    public function update(Voyage $voyage, $name, $deparatureDate, $destination)
    {
        $voyage->setName($name);
        $voyage->setStartDestination($destination);
        $voyage->setStartDate(new \DateTime($deparatureDate));

        $this->em->persist($voyage);
        $this->em->flush();

        return $voyage;
    }

}
