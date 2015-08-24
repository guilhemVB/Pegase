<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bag_item_voyage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BagItemVoyageRepository")
 */
class BagItemVoyage
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
     * @var BagItem
     * @ORM\ManyToOne(targetEntity="BagItem", inversedBy="bagItemVoyage")
     */
    private $bagItem;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var int
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var Voyage
     * @ORM\ManyToOne(targetEntity="Voyage", inversedBy="bagItemsVoyage")
     */
    private $voyage;

    /**
     * @var int
     * @ORM\Column(name="number", type="integer")
     */
    private $number= 1 ;

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
     * @param integer $price
     * @return BagItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param integer $weight
     * @return BagItem
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param BagItem $bagItem
     * @return BagItemVoyage
     */
    public function setBagItem(BagItem $bagItem = null)
    {
        $this->bagItem = $bagItem;

        return $this;
    }

    /**
     * @return BagItem
     */
    public function getBagItem()
    {
        return $this->bagItem;
    }

    /**
     * @param Voyage $voyage
     * @return BagItemVoyage
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
     * @param integer $number
     * @return BagItemVoyage
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }
}
