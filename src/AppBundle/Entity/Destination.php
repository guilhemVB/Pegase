<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *  name="destination",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="name_unique_by_county", columns={"name", "country_id"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DestinationRepository")
 */
class Destination {

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
     * @ORM\Column(name="price_stats", type="json_array", nullable=false)
     */
    private $priceStats;


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
     * @param array $priceStats
     * @return Destination
     */
    public function setPriceStats($priceStats)
    {
        $this->priceStats = $priceStats;

        return $this;
    }

    /**
     * @return array
     */
    public function getPriceStats()
    {
        return $this->priceStats;
    }
}
