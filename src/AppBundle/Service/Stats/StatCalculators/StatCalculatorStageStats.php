<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Service\Tools\DestinationPeriods;

class StatCalculatorStageStats implements StatCalculatorInterface
{

    /** @var  array */
    private $stagesStats = [];

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /** @var \DateTime|null */
    private $dateFrom;

    /** @var \DateTime */
    private $dateTo;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->dateFrom = null;
        $this->dateTo = null;
    }


    public function addStage(Stage $stage)
    {
        if (is_null($this->dateTo)) {
            $this->dateTo = $stage->getVoyage()->getStartDate();
        }

        $this->dateFrom = $this->dateTo;
        $this->dateTo = clone $this->dateFrom;
        $this->dateTo->add(new \DateInterval('P' . $stage->getNbDays() . 'D'));

        $nbStars = $this->extractNbStart($this->dateFrom, $this->dateTo, $stage->getDestination());
        $toolTipData = $this->dateFrom->format('d/m/Y') . ' - ' . $this->dateTo->format('d/m/Y');
        $this->stagesStats[$stage->getId()] = [
            'dateFrom'  => $this->dateFrom,
            'dateTo'    => $this->dateTo,
            'nbStars'   => $nbStars,
            'starsView' => $this->twig->render('AppBundle:Destination:stars.html.twig', ['nbStars' => $nbStars, 'toolTipData' => $toolTipData]),
        ];
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return ['stagesStats' => $this->stagesStats];
    }

    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param Destination $destination
     * @return int
     */
    private function extractNbStart(\DateTime $dateFrom, \DateTime $dateTo, Destination $destination)
    {
        $month = $this->extractMonthFromDates($dateFrom, $dateTo);
        $periods = $destination->getPeriods();
        return $periods[$month];
    }

    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return string
     */
    private function extractMonthFromDates(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $monthFrom = (int)($dateFrom->format('m'));
        $monthTo = (int)($dateTo->format('m'));

        if ($monthFrom === $monthTo) {
            return DestinationPeriods::getCorrelationFromMonthNumberToMonthName($monthFrom);
        }

        // more than 2 months
        if ($monthFrom + 1 !== $monthTo) {
            return DestinationPeriods::getCorrelationFromMonthNumberToMonthName($monthFrom + 1);
        }

        $nbDaysInFirstMonth = 30 - (int)($dateFrom->format('d'));
        $nbDaysInSecondMonth = (int)($dateTo->format('d'));

        return DestinationPeriods::getCorrelationFromMonthNumberToMonthName($nbDaysInFirstMonth > $nbDaysInSecondMonth ? $monthFrom : $monthTo);
    }
}
