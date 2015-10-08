<?php

namespace AppBundle\Service\Stats\StatCalculators;

use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

class StatCalculatorDates implements StatCalculatorInterface
{
    /** @var int */
    private $nbDays = 0;

    /** @var  \DateTime */
    private $startDate;

    function __construct(Voyage $voyage)
    {
        $this->startDate = $voyage->getStartDate();
    }

    public function addStage(Stage $stage)
    {
        $this->nbDays += $stage->getNbDays();
    }

    /**
     * @return array
     */
    public function getStats()
    {
        $endDate = clone $this->startDate;
        $endDate->add(new \DateInterval('P' . $this->nbDays . 'D'));

        return [
            'startDate' => $this->startDate,
            'endDate'   => $endDate,
        ];
    }
}
