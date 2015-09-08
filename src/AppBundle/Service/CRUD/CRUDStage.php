<?php

namespace AppBundle\CRUD\Service;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use Doctrine\ORM\EntityManager;

class CRUDStage
{
    /**
     * @var EntityManager
     */
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param Destination $destination
     * @param Voyage $voyage
     * @param int $nbDays
     * @return Stage
     * @throws \Exception
     */
    public function create(Destination $destination, Voyage $voyage, $nbDays)
    {
        if ($nbDays == 0) {
            throw new \Exception("nbDays cannot be empty");
        }

        $stage = new Stage();
        $stage->setDestination($destination);
        $stage->setNbDays($nbDays);
        $stage->setPosition(count($voyage->getStages()) - 1);
        $stage->setVoyage($voyage);
        $this->em->persist($stage);

        $voyage->addStage($stage);
        $this->em->persist($voyage);

        $this->em->flush();

        return $stage;
    }

    public function delete(Stage $stage)
    {
        $this->em->remove($stage);
        $this->em->flush();
    }
}
