<?php

namespace CalculatorBundle\Service\Stats;

use CalculatorBundle\Entity\Stage;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorCountries;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorCrowFliesDistance;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorDates;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorInterface;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorStageStats;

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
