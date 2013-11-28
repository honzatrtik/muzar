<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\ScraperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="scraperAdProperty",uniqueConstraints={@ORM\UniqueConstraint(name="scraperAdProperty_scraperAdId_name_UQ",columns={"scraperAdId", "name"})})
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
	 * @ORM\JoinColumn(name="scraperAdId", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 * @var Ad
	 **/
	protected $ad;

	/**
	 * @ORM\Column(type="string", length=128)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $value;

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
		$this->value = $value;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}






}