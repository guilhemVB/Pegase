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
    private $code2;

    /**
     * @var string
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $code3;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="tips", type="text")
     */
    private $tips;

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

    function __construct()
    {
        $this->destinations = new ArrayCollection();
    }

    public function getSluggableFields()
    {
        return [ 'name' ];
    }

    public function getRegenerateSlugOnUpdate() {
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
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $tips
     * @return Country
     */
    public function setTips($tips)
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * @return string
     */
    public function getTips()
    {
        return $this->tips;
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
    public function getCode2()
    {
        return $this->code2;
    }

    /**
     * @param string $code2
     * @return $this
     */
    public function setCode2($code2)
    {
        $this->code2 = $code2;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode3()
    {
        return $this->code3;
    }

    /**
     * @param string $code3
     * @return $this
     */
    public function setCode3($code3)
    {
        $this->code3 = $code3;

        return $this;
    }
}
