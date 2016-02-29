<?php

namespace CalculatorBundle\Service\Journey;


use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Service\CRUD\CRUDStage;

class BestJourneyFinder
{

    /**
     * @param AvailableJourney $availableJourney
     * @return string
     */
    public function chooseBestTransportType(AvailableJourney $availableJourney)
    {
        $transports = [];

        $busPrice = $availableJourney->getBusPrices();
        if (!is_null($busPrice)) {
            $transports[CRUDStage::BUS] = $busPrice;
        }

        $trainPrice = $availableJourney->getTrainPrices();
        if (!is_null($trainPrice)) {
            $transports[CRUDStage::TRAIN] = $trainPrice;
        }

        $flyPrice = $availableJourney->getFlyPrices();
        if (!is_null($flyPrice)) {
            $transports[CRUDStage::FLY] = $flyPrice;
        }

        if (empty($transports)) {
            return CRUDStage::NONE;
        }

        $minTransportKey = (array_keys($transports, min($transports)));

        return $minTransportKey[0];
    }
}
