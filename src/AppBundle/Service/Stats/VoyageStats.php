<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class VoyageStats
{

    /**
     * @param Voyage $voyage
     * @param Stage[] $stagesSorted
     * @return array
     */
    public function calculate(Voyage $voyage, $stagesSorted)
    {
        $nbStages = count($stagesSorted);
        $nbDays = 0;
        $countries = [];
        $crowFliesDistance = 0;
        $totalCost = 0;

        $destinationFrom = null;
        $destinationTo = null;

        foreach ($stagesSorted as $stage) {
            $destination = $stage->getDestination();
            $country = $destination->getCountry();
            
            $nbDays += $stage->getNbDays();

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
        }

        $startDate = $voyage->getStartDate();

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
        ];
    }

}
