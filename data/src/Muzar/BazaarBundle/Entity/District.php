<?php

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * District
 *
 * @ORM\Table(name="district", indexes={@ORM\Index(name="region_id", columns={"region_id"})})
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class District
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
     * @ORM\Column(name="code", type="string", length=9, nullable=false)
     * @JMS\Expose()
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @JMS\Expose()
     */
    private $name;

    /**
     * @var \Muzar\BazaarBundle\Entity\Region
     *
     * @ORM\ManyToOne(targetEntity="Muzar\BazaarBundle\Entity\Region", inversedBy="districts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    private $region;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="City", mappedBy="district")
     **/
    private $cities;

    /**
     * District constructor.
     */
    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }


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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
