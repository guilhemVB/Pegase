<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="voyage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoyageRepository")
 */
class Voyage
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
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var Destination
     * @ORM\ManyToOne(targetEntity="Destination", inversedBy="voyage")
     */
    private $startDestination;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="voyages")
     */
    private $user;

    /**
     * @var ArrayCollection|Stage[]
     * @ORM\OneToMany(targetEntity="Stage", mappedBy="voyage")
     */
    private $stages;

    /**
     * @var ArrayCollection|BagItemVoyage[]
     * @ORM\OneToMany(targetEntity="BagItemVoyage", mappedBy="voyage")
     */
    private $bagItemsVoyage;


    public function __construct()
    {
        $this->stages = new ArrayCollection();
        $this->bagItemsVoyage = new ArrayCollection();
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
     * @param \DateTime $startDate
     * @return Voyage
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param User $user
     * @return Voyage
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Stage $stages
     * @return Voyage
     */
    public function addStage(Stage $stages)
    {
        $this->stages[] = $stages;

        return $this;
    }

    /**
     * @param Stage $stages
     */
    public function removeStage(Stage $stages)
    {
        $this->stages->removeElement($stages);
    }

    /**
     * @return ArrayCollection|Stage[]
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param BagItemVoyage $bagItemsVoyage
     * @return Voyage
     */
    public function addBagItemsVoyage(BagItemVoyage $bagItemsVoyage)
    {
        $this->bagItemsVoyage[] = $bagItemsVoyage;

        return $this;
    }

    /**
     * @param BagItemVoyage $bagItemsVoyage
     */
    public function removeBagItemsVoyage(BagItemVoyage $bagItemsVoyage)
    {
        $this->bagItemsVoyage->removeElement($bagItemsVoyage);
    }

    /**
     * @return ArrayCollection|BagItemVoyage[]
     */
    public function getBagItemsVoyage()
    {
        return $this->bagItemsVoyage;
    }

    /**
     * @param Destination $startDestination
     * @return Voyage
     */
    public function setStartDestination(Destination $startDestination = null)
    {
        $this->startDestination = $startDestination;

        return $this;
    }

    /**
     * @return Destination
     */
    public function getStartDestination()
    {
        return $this->startDestination;
    }
}
