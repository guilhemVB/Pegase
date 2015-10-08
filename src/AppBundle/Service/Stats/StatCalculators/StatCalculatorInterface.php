<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;

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
