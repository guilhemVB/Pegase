<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="traveller")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TravellerRepository")
 */
class Traveller
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var Voyage
     * @ORM\ManyToOne(targetEntity="Voyage", inversedBy="travellers")
     */
    private $voyage;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Voyage $voyage
     * @return Traveller
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

}
