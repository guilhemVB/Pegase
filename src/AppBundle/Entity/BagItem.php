<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bag_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BagItemRepository")
 */
class BagItem
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
     * @var float
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

    /**
     * @var int
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity= 1 ;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return BagItem
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
     * @param float $price
     * @return BagItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $weight
     * @return BagItem
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param integer $quantity
     * @return BagItemVoyage
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
