<?php

namespace CalculatorBundle\Entity;

use AppBundle\Entity\Destination;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="available_journey")
 * @ORM\Entity(repositoryClass="CalculatorBundle\Repository\AvailableJourneyRepository")
 */
class AvailableJourney
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Destination")
     */
    private $fromDestination;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Destination")
     */
    private $toDestination;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $flyPrices;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=true)
     */
    private $flyTime;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $busPrices;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=true)
     */
    private $busTime;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $trainPrices;

    /**
     * @var \DateTime
     * @ORM\Column(type="time", nullable=true)
     */
    private $trainTime;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Destination
     */
    public function getFromDestination()
    {
        return $this->fromDestination;
    }

    /**
     * @param Destination $fromDestination
     * @return $this
     */
    public function setFromDestination($fromDestination)
    {
        $this->fromDestination = $fromDestination;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToDestination()
    {
        return $this->toDestination;
    }

    /**
     * @param mixed $toDestination
     * @return $this
     */
    public function setToDestination($toDestination)
    {
        $this->toDestination = $toDestination;

        return $this;
    }

    /**
     * @return float
     */
    public function getFlyPrices()
    {
        return $this->flyPrices;
    }

    /**
     * @param float $flyPrices
     * @return $this
     */
    public function setFlyPrices($flyPrices)
    {
        $this->flyPrices = $flyPrices;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFlyTime()
    {
        return $this->flyTime;
    }

    /**
     * @param mixed $flyTime
     * @return $this
     */
    public function setFlyTime($flyTime)
    {
        $this->flyTime = $flyTime;

        return $this;
    }

    /**
     * @return float
     */
    public function getBusPrices()
    {
        return $this->busPrices;
    }

    /**
     * @param float $busPrices
     * @return $this
     */
    public function setBusPrices($busPrices)
    {
        $this->busPrices = $busPrices;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBusTime()
    {
        return $this->busTime;
    }

    /**
     * @param \DateTime $busTime
     * @return $this
     */
    public function setBusTime($busTime)
    {
        $this->busTime = $busTime;

        return $this;
    }

    /**
     * @return float
     */
    public function getTrainPrices()
    {
        return $this->trainPrices;
    }

    /**
     * @param float $trainPrices
     * @return $this
     */
    public function setTrainPrices($trainPrices)
    {
        $this->trainPrices = $trainPrices;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTrainTime()
    {
        return $this->trainTime;
    }

    /**
     * @param \DateTime $trainTime
     * @return $this
     */
    public function setTrainTime($trainTime)
    {
        $this->trainTime = $trainTime;

        return $this;
    }

}
