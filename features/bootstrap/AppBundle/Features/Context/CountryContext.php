<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Country;
use AppKernel;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
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
            $country->setName($countryRow['nom'])
                ->setCapitalName($countryRow['capitale'])
                ->setCurrency($this->findCurrencyByCode($countryRow['monnaie']))
                ->setVisaInformation(isset($countryRow['visa']) ? $countryRow['visa'] : 'Visa gratuit pour la France')
                ->setVisaDuration(isset($countryRow['durée du visa']) ? $countryRow['durée du visa'] : '3 mois')
                ->setPriceAccommodation(isset($countryRow["prix de l'hébergement"]) ? $countryRow["prix de l'hébergement"] : null)
                ->setPriceLifeCost(isset($countryRow["prix du cout de la vie"]) ? $countryRow["prix du cout de la vie"] : null)
                ->setCodeAlpha3(isset($countryRow["CodeAlpha3"]) ? $countryRow["CodeAlpha3"] : null);

            $this->em->persist($country);
        }
        $this->em->flush();
    }

    /**
     * @Given les destinations par défaut :
     */
    public function lesDestinationsParDefaut(TableNode $tableCountries)
    {
        foreach ($tableCountries as $countryRow) {
            $country = $this->findCountryByName($countryRow['pays']);
            $destination = $this->findDestinationByName($countryRow['destination']);

            $country->setDefaultDestination($destination);

            $this->em->persist($country);
        }
        $this->em->flush();
    }


}
