<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\Stage;

class StatCalculatorDestinations implements StatCalculatorInterface
{
    /** @var Stage */
    private $mainStage = null;

    /** @var int */
    private $maxNumberDays = -1;

    public function addStage(Stage $stage)
    {
        $nbDays = $stage->getNbDays();

        if ($nbDays > $this->maxNumberDays) {
            $this->maxNumberDays = $nbDays;
            $this->mainStage = $stage;
        }
    }

    /**
     * @return array
     */
    public function getStats()
    {
        $data = [];
        if (!is_null($this->mainStage)) {
            $destination = $this->mainStage->getDestination();
            $data = [
                'id' => $destination->getId(),
                'name' => $destination->getName(),
                'slug' => $destination->getSlug(),
                'stageId' => $this->mainStage->getId(),
            ];
        }

        return ['mainDestination' => $data];
    }
}
