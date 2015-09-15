<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;
use AppBundle\Service\Tools\DestinationPeriods;

class VoyageStats
{

    /**
     * @param Voyage $voyage
     * @param Stage[] $stagesSorted
     * @return array
     */
    public function calculate(Voyage $voyage, $stagesSorted)
    {
        $startDate = $voyage->getStartDate();

        $nbStages = count($stagesSorted);
        $nbDays = 0;
        $countries = [];
        $stagesStats = [];
        $crowFliesDistance = 0;
        $totalCost = 0;

        $destinationFrom = null;
        $destinationTo = null;

        $dateFrom = null;
        $dateTo = $startDate;

        foreach ($stagesSorted as $stage) {

            $destination = $stage->getDestination();
            $country = $destination->getCountry();

            $nbDays += $stage->getNbDays();

            $dateFrom = $dateTo;
            $dateTo = clone $dateFrom;
            $dateTo->add(new \DateInterval('P' . $stage->getNbDays() . 'D'));

            $countries[$country->getName()] =
                isset($countries[$country->getName()]) ?
                    $countries[$country->getName()] + 1 :
                    1;

            $destinationFrom = $destinationTo;
            $destinationTo = $destination;

            if (!is_null($destinationFrom) && !is_null($destinationTo)) {
                $crowFliesDistance += CrowFliesCalculator::calculate($destinationFrom, $destinationTo);
            }

            $prices = $destination->getPrices();
            $totalCost += ($prices['accommodation'] + $prices['life cost']) * $stage->getNbDays();

            $stagesStats[$stage->getId()] = [
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
                'nbStars'  => $this->extractNbStart($dateFrom, $dateTo, $destination),
            ];
        }

        $endDate = clone $startDate;
        $endDate->add(new \DateInterval('P' . $nbDays . 'D'));

        return [
            'nbStages'          => $nbStages,
            'nbDays'            => $nbDays,
            'nbCountries'       => count($countries),
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'crowFliesDistance' => $crowFliesDistance,
            'totalCost'         => $totalCost,
            'stagesStats'       => $stagesStats,
        ];
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
