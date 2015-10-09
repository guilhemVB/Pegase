<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;

class StatCalculatorPrices implements StatCalculatorInterface
{
    /** @var int */
    private $totalCostAccommodation = 0;

    /** @var int */
    private $totalCostLifeCost = 0;

    public function addStage(Stage $stage)
    {
        $prices = $stage->getDestination()->getPrices();
        $nbDays = $stage->getNbDays();
        $this->totalCostAccommodation += $prices['accommodation'] * $nbDays;
        $this->totalCostLifeCost += $prices['life cost'] * $nbDays;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return [
            'totalCost'              => $this->totalCostAccommodation + $this->totalCostLifeCost,
            'totalCostAccommodation' => $this->totalCostAccommodation,
            'totalCostLifeCost'      => $this->totalCostLifeCost,
        ];
    }
}
