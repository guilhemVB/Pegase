<?php

namespace CalculatorBundle\Entity;

use CalculatorBundle\Service\CRUD\CRUDStage;

trait JourneyTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transportType;

    /**
     * @var AvailableJourney
     * @ORM\ManyToOne(targetEntity="CalculatorBundle\Entity\AvailableJourney")
     */
    private $availableJourney;

    /**
     * @return AvailableJourney
     */
    public function getAvailableJourney()
    {
        return $this->availableJourney;
    }

    /**
     * @param AvailableJourney $availableJourney
     * @return $this
     */
    public function setAvailableJourney($availableJourney)
    {
        $this->availableJourney = $availableJourney;

        return $this;
    }

    /**
     * @param string $transportType
     * @return $this
     * @throws \Exception
     */
    public function setTransportType($transportType)
    {
        if (is_null($transportType) || CRUDStage::BUS === $transportType || CRUDStage::TRAIN === $transportType || CRUDStage::FLY === $transportType || CRUDStage::NONE === $transportType) {
            $this->transportType = $transportType;
        } else {
            throw new \Exception("Unknow transportType '$transportType''");
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    public function getPriceAndTimeTransport()
    {
        $availableJourney = $this->getAvailableJourney();
        $transportType = $this->getTransportType();

        if (empty($transportType)) {
            return null;
        }

        $price = null;
        $time = null;

        switch ($transportType) {
            case CRUDStage::BUS :
                $price = $availableJourney->getBusPrices();
                $time = $availableJourney->getBusTime();
                break;
            case CRUDStage::TRAIN :
                $price = $availableJourney->getTrainPrices();
                $time = $availableJourney->getTrainTime();
                break;
            case CRUDStage::FLY :
                $price = $availableJourney->getFlyPrices();
                $time = $availableJourney->getFlyTime();
                break;
            case CRUDStage::NONE :
                $price = 0;
                $time = 0;
                break;
            default :
                throw new \Exception("Unknow transportType '$transportType''");
        }

        return ['price' => $price, 'time' => $time, 'transportType' => $transportType];
    }

}
