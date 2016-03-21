<?php

namespace CalculatorBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Entity\Voyage;

class StatCalculatorDestinations implements StatCalculatorInterface
{
    /** @var array */
    private $stagesWithNbDays = [];

    /** @var int */
    private $nbDestinationToReturn;

    public function __construct($nbDestinationToReturn = 1)
    {
        $this->nbDestinationToReturn = $nbDestinationToReturn;
    }

    public function addFirstStep(Voyage $voyage)
    {
        $this->stagesWithNbDays[] = [
            'destination' => $voyage->getStartDestination(),
            'nbDays' => 0,
        ];
    }

    public function addStage(Stage $stage)
    {
        foreach ($this->stagesWithNbDays as &$stageWithNbDays) {
            /** @var Destination $destination */
            $destination = $stageWithNbDays['destination'];
            if ($destination->getId() == $stage->getDestination()->getId()) {
                $stageWithNbDays['nbDays'] += $stage->getNbDays();
                return;
            }
        }

        $this->stagesWithNbDays[] = [
            'destination' => $stage->getDestination(),
            'nbDays' => $stage->getNbDays(),
        ];
    }

    /**
     * @return array
     */
    public function getStats()
    {
        usort($this->stagesWithNbDays, function($a, $b) {
            return $b['nbDays'] - $a['nbDays'];
        });

        $data = [];
        if (1 == $this->nbDestinationToReturn) {
            if (isset($this->stagesWithNbDays[0])) {
                /** @var Destination $destination */
                $destination = $this->stagesWithNbDays[0]['destination'];
                $data = [
                    'id' => $destination->getId(),
                    'name' => $destination->getName(),
                    'slug' => $destination->getSlug(),
                ];
            }

            return ['mainDestination' => $data];
        } elseif($this->nbDestinationToReturn >= 2) {
            for($i = 0; $i < $this->nbDestinationToReturn ; $i++) {
                if (isset($this->stagesWithNbDays[$i])) {
                    /** @var Destination $destination */
                    $destination = $this->stagesWithNbDays[$i]['destination'];
                    $data[] = [
                        'id' => $destination->getId(),
                        'name' => $destination->getName(),
                        'slug' => $destination->getSlug(),
                    ];
                }
            }
        }

        return ['mainDestinations' => $data];
    }
}
