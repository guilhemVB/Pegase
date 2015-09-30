<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Destination;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DestinationContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @Given les destinations :
     */
    public function lesDestinations(TableNode $tableDestinations)
    {
        foreach ($tableDestinations as $destinationRow) {
            $country = $this->findCountryByName($destinationRow['pays']);

            $destination = new Destination();
            $destination->setName($destinationRow['nom']);
            $destination->setDescription($destinationRow['description']);
            $destination->setTips($destinationRow['bon plans']);
            $destination->setPeriods(json_encode($destinationRow['périodes']));
            $destination->setPrices(json_encode($destinationRow['prix']));
            $destination->setLongitude($destinationRow['longitude']);
            $destination->setLatitude($destinationRow['latitude']);
            $destination->setCountry($country);

            $this->em->persist($destination);

            $country->addDestination($destination);

            $this->em->persist($country);
        }
        $this->em->flush();
    }
}
