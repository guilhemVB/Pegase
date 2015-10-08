<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorInterface;
use AppBundle\Service\Tools\DestinationPeriods;

class VoyageStats
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Voyage $voyage
     * @param Stage[] $stagesSorted
     * @param StatCalculatorInterface[] $statCalculators
     * @return array
     */
    public function calculate(Voyage $voyage, $stagesSorted, $statCalculators)
    {
        foreach ($stagesSorted as $stage) {
            foreach ($statCalculators as $statCalculator) {
                $statCalculator->addStage($stage);
            }
        }

        $stats = [
            'nbStages'  => count($stagesSorted)
        ];

        foreach ($statCalculators as $statCalculator) {
            $stats = array_merge($stats, $statCalculator->getStats());
        }

        return $stats;
    }

}
