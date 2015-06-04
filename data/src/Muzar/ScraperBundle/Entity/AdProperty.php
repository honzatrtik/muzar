<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\ScraperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="scraper_ad_property",uniqueConstraints={@ORM\UniqueConstraint(name="scraper_ad_property_scraper_ad_id_name_UQ",columns={"scraper_ad_id", "name"})})
 */
class AdProperty
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Ad")
	 * @ORM\JoinColumn(name="scraper_ad_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var Ad
	 **/
	protected $ad;

	/**
	 * @ORM\Column(type="string", length=128)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $valueSerialized;

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
	 * @param \Muzar\ScraperBundle\Entity\Ad $ad
	 */
	public function setAd(Ad $ad)
	{
		$this->ad = $ad;
		return $this;
	}

	/**
	 * @return \Muzar\ScraperBundle\Entity\Ad
	 */
	public function getAd()
	{
		return $this->ad;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		if (is_scalar($value))
		{
			$this->value = $value;
		}
		else
		{
			$this->valueSerialized = serialize($value);
		}
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{

		return $this->valueSerialized
			? unserialize($this->valueSerialized)
			: $this->value;
	}
}