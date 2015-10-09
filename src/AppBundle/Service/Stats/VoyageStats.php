<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorCountries;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorCrowFliesDistance;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorDates;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorInterface;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use AppBundle\Service\Stats\StatCalculators\StatCalculatorStageStats;

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
     * @param Stage[] $stagesSorted
     * @param StatCalculatorInterface[] $statCalculators
     * @return array
     */
    public function calculate($stagesSorted, $statCalculators)
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

    /**
     * @param Stage[] $stagesSorted
     * @return array
     */
    public function calculateAllStats($stagesSorted)
    {
        $statCalculators = [
            new StatCalculatorCountries(),
            new StatCalculatorCrowFliesDistance(),
            new StatCalculatorDates(),
            new StatCalculatorNumberDays(),
            new StatCalculatorPrices(),
            new StatCalculatorStageStats($this->twig),
        ];
        return $this->calculate($stagesSorted, $statCalculators);
    }

}
