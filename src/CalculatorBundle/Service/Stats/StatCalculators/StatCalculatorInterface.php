<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use CalculatorBundle\Entity\Stage;

interface StatCalculatorInterface
{

    /**
     * @param Stage $stage
     * @return mixed
     */
    public function addStage(Stage $stage);

    /**
     * @return array
     */
    public function getStats();
}
