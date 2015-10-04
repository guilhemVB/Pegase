<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(
 *  name="destination",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="name_unique_by_country", columns={"name", "country_id"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DestinationRepository")
 */
class Destination
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
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isTheCapital = false;

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
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="destinations")
     */
    private $country;

    /**
     * @var array
     * @ORM\Column(name="periods", type="json_array", nullable=false)
     */
    private $periods;

    /**
     * @var array
     * @ORM\Column(name="prices", type="json_array", nullable=false)
     */
    private $prices;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $longitude;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     */
    private $latitude;


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
     * @return Destination
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
     * @return bool
     */
    public function isTheCapital()
    {
        return $this->isTheCapital;
    }

    /**
     * @param bool $isTheCapital
     * @return $this
     */
    public function setIsTheCapital($isTheCapital)
    {
        $this->isTheCapital = $isTheCapital;

        return $this;
    }

    /**
     * @param Country $country
     * @return Destination
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $description
     * @return Destination
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
     * @param array $periods
     * @return Destination
     */
    public function setPeriods($periods)
    {
        $this->periods = $periods;

        return $this;
    }

    /**
     * @return array
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * @param array $prices
     * @return Destination
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @return array
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param float $longitude
     * @return Destination
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
     * @return Destination
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
     * @param string $tips
     * @return Destination
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
