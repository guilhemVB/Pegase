<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country
{

    use ORMBehaviors\Sluggable\Sluggable;

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
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $codeAlpha2;

    /**
     * @var string
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $codeAlpha3;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $population;

    /**
     * @var ArrayCollection|Destination[]
     * @ORM\OneToMany(targetEntity="Destination", mappedBy="country")
     */
    private $destinations;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $redirectToDestination = false;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    function __construct()
    {
        $this->destinations = new ArrayCollection();
    }

    public function getSluggableFields()
    {
        return ['name'];
    }

    public function getRegenerateSlugOnUpdate()
    {
        return false;
    }

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
     * @param Destination $destinations
     * @return Country
     */
    public function addDestination(Destination $destinations)
    {
        $this->destinations[] = $destinations;

        return $this;
    }

    /**
     * @param Destination $destinations
     */
    public function removeDestination(Destination $destinations)
    {
        $this->destinations->removeElement($destinations);
    }

    /**
     * @return ArrayCollection|Destination[]
     */
    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * @return boolean
     */
    public function isRedirectToDestination()
    {
        return $this->redirectToDestination;
    }

    /**
     * @param boolean $redirectToDestination
     * @return $this
     */
    public function setRedirectToDestination($redirectToDestination)
    {
        $this->redirectToDestination = $redirectToDestination;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeAlpha2()
    {
        return $this->codeAlpha2;
    }

    /**
     * @param string $codeAlpha2
     * @return $this
     */
    public function setCodeAlpha2($codeAlpha2)
    {
        $this->codeAlpha2 = $codeAlpha2;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeAlpha3()
    {
        return $this->codeAlpha3;
    }

    /**
     * @param string $codeAlpha3
     * @return $this
     */
    public function setCodeAlpha3($codeAlpha3)
    {
        $this->codeAlpha3 = $codeAlpha3;

        return $this;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return integer
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * @param integer $population
     * @return $this
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }
}
