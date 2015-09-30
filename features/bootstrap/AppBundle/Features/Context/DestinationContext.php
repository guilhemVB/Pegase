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
            $destination->setDescription(isset($destinationRow['description']) ? $destinationRow['description'] : '');
            $destination->setTips(isset($destinationRow['bon plans']) ? $destinationRow['bon plans'] : '');
            $destination->setPeriods(isset($destinationRow['périodes']) ? json_encode($destinationRow['périodes']) : json_encode('{"january":"1","february":"1","march":"2","april":"2","may":"2","june":"3","july":"3","august":"3","september":"2","october":"1","november":"1","december":"1"}'));
            $destination->setPrices(isset($destinationRow['prix']) ? json_encode($destinationRow['prix']) : json_encode('{"accommodation":"32","life cost":"24"}'));
            $destination->setLongitude(isset($destinationRow['longitude']) ? $destinationRow['longitude'] : 2.336492);
            $destination->setLatitude(isset($destinationRow['latitude']) ? $destinationRow['latitude'] : 48.864592);
            $destination->setCountry($country);

            $this->em->persist($destination);

            $country->addDestination($destination);

            $this->em->persist($country);
        }
        $this->em->flush();
    }
}
