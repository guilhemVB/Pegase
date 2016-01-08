<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use CalculatorBundle\Entity\Stage;

class StatCalculatorPrices implements StatCalculatorInterface
{
    /** @var int */
    private $totalCostAccommodation = 0;

    /** @var int */
    private $totalCostLifeCost = 0;

    public function addStage(Stage $stage)
    {
        $priceAccommodation = $stage->getDestination()->getPriceAccommodation();
        $priceLifeCost = $stage->getDestination()->getPriceLifeCost();
        $nbDays = $stage->getNbDays();
        $this->totalCostAccommodation += $priceAccommodation * $nbDays;
        $this->totalCostLifeCost += $priceLifeCost * $nbDays;
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
