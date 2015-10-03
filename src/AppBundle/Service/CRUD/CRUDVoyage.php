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
     * @param int $nbTraveller
     * @return Voyage
     */
    public function add(User $user, $name, $deparatureDate, $destination, $nbTraveller)
    {
        $voyage = new Voyage();
        $voyage->setUser($user);
        $voyage->setName($name);
        $voyage->setStartDate(new \DateTime($deparatureDate));
        $voyage->setStartDestination($destination);

        for ($i = 0; $i < $nbTraveller; $i++) {
            $traveller = new Traveller();
            $traveller->setName('Voyageur ' . ($i + 1));
            $traveller->setVoyage($voyage);
            $voyage->addTraveller($traveller);

            $this->em->persist($traveller);
        }

        $this->em->persist($voyage);
        $this->em->flush();

        return $voyage;
    }

}