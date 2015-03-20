<?php

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostalCode
 *
 * @ORM\Table(name="postal_code", indexes={@ORM\Index(name="district_id", columns={"district_id"}), @ORM\Index(name="region_id", columns={"region_id"})})
 * @ORM\Entity
 */
class PostalCode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=5, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_office_name", type="string", length=80, nullable=false)
     */
    private $postalOfficeName;

    /**
     * @var \Muzar\BazaarBundle\Entity\District
     *
     * @ORM\ManyToOne(targetEntity="Muzar\BazaarBundle\Entity\District")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     * })
     */
    private $district;

    /**
     * @var \Muzar\BazaarBundle\Entity\Region
     *
     * @ORM\ManyToOne(targetEntity="Muzar\BazaarBundle\Entity\Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    private $region;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalOfficeName()
    {
        return $this->postalOfficeName;
    }

    /**
     * @param string $postalOfficeName
     */
    public function setPostalOfficeName($postalOfficeName)
    {
        $this->postalOfficeName = $postalOfficeName;
        return $this;
    }

    /**
     * @return District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param District $district
     */
    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }




}
