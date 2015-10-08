<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;

class StatCalculatorPrices implements StatCalculatorInterface
{
    /** @var int */
    private $totalCost = 0;

    public function addStage(Stage $stage)
    {
        $prices = $stage->getDestination()->getPrices();
        $this->totalCost += ($prices['accommodation'] + $prices['life cost']) * $stage->getNbDays();
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['totalCost' => $this->totalCost];
    }
}
