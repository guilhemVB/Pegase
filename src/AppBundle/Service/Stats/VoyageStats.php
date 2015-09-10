<?php

namespace AppBundle\Service\Stats;

use AppBundle\Entity\Voyage;

class VoyageStats
{

    /**
     * @param Voyage $voyage
     * @return array
     */
    public function calculate(Voyage $voyage)
    {
        $stages = $voyage->getStages();

        $nbStages = count($stages);
        $nbDays = 0;
        $countries = [];
        $crowFliesDistance = 0;

        $destinationFrom = null;
        $destinationTo = null;

        foreach ($stages as $stage) {
            $nbDays += $stage->getNbDays();
            $destination = $stage->getDestination();
            $country = $destination->getCountry();
            $countries[$country->getName()] =
                isset($countries[$country->getName()]) ?
                    $countries[$country->getName()] + 1 :
                    1;
            $destinationFrom = $destinationTo;
            $destinationTo = $destination;

            if (!is_null($destinationFrom) && !is_null($destinationTo)) {
                $crowFliesDistance += CrowFliesCalculator::calculate($destinationFrom, $destinationTo);
            }
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
        ];
    }

}
