<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Entity\Voyage;
use CalculatorBundle\Service\Stats\CrowFliesCalculator;

class StatCalculatorCrowFliesDistance implements StatCalculatorInterface
{
    /** @var Destination|null */
    private $destinationFrom = null;

    /** @var Destination|null */
    private $destinationTo = null;

    /** @var int */
    private $crowFliesDistance = 0;

    public function addFirstStep(Voyage $voyage)
    {
        $this->calculateCrowDistance($voyage->getStartDestination());
    }

    public function addStage(Stage $stage)
    {
        if (!is_null($stage->getCountry())) {
            $defaultDestination = $stage->getCountry()->getDefaultDestination();
        } else {
            $defaultDestination = $stage->getDestination();
        }
        $this->calculateCrowDistance($defaultDestination);
    }

    /**
     * @param Destination $destination
     */
    private function calculateCrowDistance(Destination $destination)
    {
        $this->destinationFrom = $this->destinationTo;
        $this->destinationTo = $destination;

        if (!is_null($this->destinationFrom) && !is_null($this->destinationTo)) {
            $this->crowFliesDistance += CrowFliesCalculator::calculate($this->destinationFrom, $this->destinationTo);
        }

    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['crowFliesDistance' => $this->crowFliesDistance];
    }
}
