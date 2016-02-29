<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Entity\Voyage;

interface StatCalculatorInterface
{

    /**
     * @param Voyage $voyage
     * @return mixed
     */
    public function addFirstStep(Voyage $voyage);

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
