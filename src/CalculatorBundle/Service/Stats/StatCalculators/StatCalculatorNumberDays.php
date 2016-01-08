<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use CalculatorBundle\Entity\Stage;

class StatCalculatorNumberDays implements StatCalculatorInterface
{
    /** @var int */
    private $nbDays = 0;


    public function addStage(Stage $stage)
    {
        $this->nbDays += $stage->getNbDays();
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['nbDays' => $this->nbDays];
    }
}
