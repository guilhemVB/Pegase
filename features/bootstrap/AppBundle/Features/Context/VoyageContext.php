<?php

namespace AppBundle\Features\Context;

use AppBundle\Service\CRUD\CRUDVoyage;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VoyageContext extends CommonContext
{
    /** @var CRUDVoyage */
    private $CRUDVoyage;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->CRUDVoyage = $container->get('crud_voyage');
    }

    /**
     * @Given les voyages de l'utilisateur :userName
     */
    public function lesVoyagesDeLUtilisateur($userName, TableNode $tableVoyages)
    {
        $user = $this->findUserByName($userName);
        foreach ($tableVoyages as $voyageRow) {
            $destination = $this->findDestinationByName($voyageRow['destination de départ']);
            $this->CRUDVoyage->add($user, $voyageRow['nom'], $voyageRow['date de départ'], $destination, $voyageRow['nombre de voyageur']);
        }
    }
}
