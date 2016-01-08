<?php

namespace AppBundle\Entity;

use CalculatorBundle\Entity\Voyage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Table(name="user_travel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|Voyage[]
     * @ORM\OneToMany(targetEntity="CalculatorBundle\Entity\Voyage", mappedBy="user")
     */
    private $voyages;

    public function __construct()
    {
        parent::__construct();
        $this->voyages = new ArrayCollection();
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
