<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;

class StatCalculatorCountries implements StatCalculatorInterface
{
    /** @var array */
    private $countries = [];

    public function addStage(Stage $stage)
    {
        $country = $stage->getDestination()->getCountry();

        $countries[$country->getName()] =
            isset($countries[$country->getName()]) ?
                $countries[$country->getName()] + 1 :
                1;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['nbCountries' => count($this->countries)];
    }
}
