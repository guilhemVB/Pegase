<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Service\Stats\CrowFliesCalculator;

class StatCalculatorCrowFliesDistance implements StatCalculatorInterface
{
    /** @var Destination|null */
    private $destinationFrom = null;

    /** @var Destination|null */
    private $destinationTo = null;

    /** @var int */
    private $crowFliesDistance = 0;

    public function addStage(Stage $stage)
    {
        $destination = $stage->getDestination();

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
