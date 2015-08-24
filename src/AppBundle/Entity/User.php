<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection|Voyage[]
     * @ORM\OneToMany(targetEntity="Voyage", mappedBy="user")
     */
    private $voyages;


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
     * Constructor
     */
    public function __construct()
    {
        $this->voyages = new ArrayCollection();
    }

    /**
     * @param Voyage $voyage
     * @return User
     */
    public function addVoyage(Voyage $voyage)
    {
        $this->voyages[] = $voyage;

        return $this;
    }

    /**
     * @param Voyage $voyage
     */
    public function removeVoyage(Voyage $voyage)
    {
        $this->voyages->removeElement($voyage);
    }

    /**
     * @return ArrayCollection|Voyage[]
     */
    public function getVoyages()
    {
        return $this->voyages;
    }
}
