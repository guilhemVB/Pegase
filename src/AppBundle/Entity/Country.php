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
}
