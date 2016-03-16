<?php

namespace CalculatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="typical_voyage")
 * @ORM\Entity(repositoryClass="CalculatorBundle\Repository\TypicalVoyageRepository")
 */
class TypicalVoyage
{

    use ORMBehaviors\Timestampable\Timestampable;

    const CART_MAIN = "MAIN";
    const CART_EUROPE = "EUROPE";
    const CART_ASIA = "ASIA";
    const CART_AMERICA = "AMERICA";
    const CART_WORLD_TOUR = "WORLD_TOUR";

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
     * @ORM\OneToOne(targetEntity="Voyage")
     */
    private $voyage;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbDays = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price = 0;

    private $category;


    public function __construct()
    {
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Voyage
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * @param Voyage $voyage
     * @return $this
     */
    public function setVoyage($voyage)
    {
        $this->voyage = $voyage;

        return$this;
    }

    /**
     * @return int
     */
    public function getNbDays()
    {
        return $this->nbDays;
    }

    /**
     * @param int $nbDays
     * @return $this
     */
    public function setNbDays($nbDays)
    {
        $this->nbDays = $nbDays;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        if ($category !== self::CART_MAIN && $category !== self::CART_AMERICA && $category !== self::CART_ASIA
            && $category !== self::CART_EUROPE && $category !== self::CART_WORLD_TOUR) {
            throw new \InvalidArgumentException("Category $category doesn't exist.");
        }

        $this->category = $category;

        return $this;
    }

}
