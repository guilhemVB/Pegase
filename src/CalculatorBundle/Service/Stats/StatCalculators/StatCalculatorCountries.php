<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use CalculatorBundle\Entity\Stage;

class StatCalculatorCountries implements StatCalculatorInterface
{
    /** @var array */
    private $countries = [];

    public function addStage(Stage $stage)
    {
        $country = $stage->getDestination()->getCountry();

        $this->countries[$country->getName()] =
            isset($this->countries[$country->getName()]) ?
                $this->countries[$country->getName()] + 1 :
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
