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
        foreach ($tableDestinations->getHash() as $destinationRow) {
            $country = $this->findCountryByName($destinationRow['pays']);

            $destination = new Destination();
            $destination->setName($destinationRow['nom'])
                ->setDescription(isset($destinationRow['description']) ? [$destinationRow['description']] : [])
                ->setTips(isset($destinationRow['bon plans']) ? $destinationRow['bon plans'] : '')
                ->setPriceAccommodation(isset($destinationRow["prix de l'hébergement"]) ? $destinationRow["prix de l'hébergement"] : 0)
                ->setPriceLifeCost(isset($destinationRow["prix du cout de la vie"]) ? $destinationRow["prix du cout de la vie"] : 0)
                ->setLongitude(isset($destinationRow['longitude']) ? $destinationRow['longitude'] : 2.336492)
                ->setLatitude(isset($destinationRow['latitude']) ? $destinationRow['latitude'] : 48.864592)
                ->setCountry($country);

            $this->em->persist($destination);

            $country->addDestination($destination);

            $this->em->persist($country);
        }
        $this->em->flush();
    }
}
