<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\ScraperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="\Muzar\ScraperBundle\Entity\AdRepository")
 * @ORM\Table(name="scraper_ad",uniqueConstraints={@ORM\UniqueConstraint(name="scraperAd_link_UQ",columns={"link"})})
 */
class Ad
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $link;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $source;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime
	 */
	protected $parsed;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $created;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 * @var int
	 */
	protected $status;

	/**
	 * @ORM\OneToMany(targetEntity="AdProperty", mappedBy="ad", cascade={"persist"})
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	protected $properties;


	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param mixed $source
	 */
	public function setSource($source)
	{
		$this->source = $source;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSource()
	{
		return $this->source;
	}



	/**
	 * @param \DateTime $parsed
	 */
	public function setParsed(\DateTime $parsed)
	{
		$this->parsed = $parsed;
		return $this;
	}

	/**
	 * @param string $format
	 * @return \DateTime
	 */
	public function getParsed($format = NULL)
	{
		return ($this->parsed && $format)
			? $this->parsed->format($format)
			: $this->parsed;

	}

	/**
	 * @param \DateTime $created
	 */
	public function setCreated(\DateTime $created)
	{
		$this->created = $created;
		return $this;
	}

	/**
	 * @param string $format
	 * @return \DateTime
	 */
	public function getCreated($format = NULL)
	{
		return ($this->created && $format)
			? $this->created->format($format)
			: $this->created;

	}

	/**
	 * @param int $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param AdProperty $property
	 * @return $this
	 */
	public function addProperty(AdProperty $property)
	{
		$this->properties->add($property);
		$property->setAd($this);
		return $this;
	}

	/**
	 * @param $name
	 * @return AdProperty
	 */
	public function getPropertyByName($name)
	{
		return $this->properties->filter(function(AdProperty $p) use ($name) {
			return $p->getName() == $name;
		})->first();
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getPropertyValueByName($name, $default = NULL)
	{
		$property = $this->getPropertyByName($name);
		return $property
			? $property->getValue()
			: $default;
	}

	/**
	 * @param $name
	 * @param $value
	 * @return $this
	 */
	public function addPropertyByName($name, $value)
	{
		if ($property = $this->getPropertyByName($name))
		{
			$property->setValue($value);
		}
		else
		{
			$property = new AdProperty();
			$property->setName($name);
			$property->setValue($value);
			$this->addProperty($property);
		}
		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getProperties()
	{
		return $this->properties;
	}

}