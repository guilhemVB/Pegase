<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="stage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StageRepository")
 */
class Stage {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Voyage
     * @ORM\ManyToOne(targetEntity="Voyage", inversedBy="stages")
     */
    private $voyage;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination", inversedBy="stages")
     */
    private $destination;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $nbDays;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $position;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param Voyage $voyage
     * @return Stage
     */
    public function setVoyage(Voyage $voyage = null)
    {
        $this->voyage = $voyage;

        return $this;
    }

    /**
     * @return Voyage
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * @param Destination $destination
     * @return Stage
     */
    public function setDestination(Destination $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return Destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param int $position
     * @return Stage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param float $nbDays
     * @return Stage
     */
    public function setNbDays($nbDays)
    {
        $this->nbDays = $nbDays;

        return $this;
    }

    /**
     * @return float
     */
    public function getNbDays()
    {
        return $this->nbDays;
    }
}
