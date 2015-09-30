<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Country;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountryContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @Given les pays :
     */
    public function lesPays(TableNode $tableCountries)
    {
        foreach ($tableCountries as $countryRow) {
            $country = new Country();
            $country->setName($countryRow['nom']);
            $country->setDescription(isset($countryRow['description']) ? $countryRow['description'] : '');
            $country->setTips(isset($countryRow['bon plans']) ? $countryRow['bon plans'] : '');

            $this->em->persist($country);
        }
        $this->em->flush();
    }


}
