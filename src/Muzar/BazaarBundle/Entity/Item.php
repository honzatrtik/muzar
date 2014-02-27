<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Entity(repositoryClass="Muzar\BazaarBundle\Entity\ItemRepository")
 * @ORM\Table(name="item", indexes={@ORM\Index(name="item_status_idx",columns={"status"})})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Item
{
	const STATUS_ACTIVE = 'active';
	const STATUS_EXPIRED = 'expired';
	const STATUS_SOLD = 'sold';

	protected static $states = array(
		self::STATUS_ACTIVE,
		self::STATUS_EXPIRED,
		self::STATUS_SOLD,
	);

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose()
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=32)
	 * @JMS\Expose()
	 */
	protected $status = self::STATUS_ACTIVE;

	/**
	 * @ORM\Column(type="string", length=1024)
	 * @JMS\Expose()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $description;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
	 * @JMS\Expose()
	 */
	protected $price;


	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 * @JMS\Expose()
	 */
	protected $created;

	/**
	 * ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
	 **/
	private $categories;


	function __construct()
	{
		$this->categories = new ArrayCollection();
	}


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
	 * @return bool
	 */
	public function isActive()
	{
		return $this->getStatus() === self::STATUS_ACTIVE;
	}


	/**
	 * @param mixed $status
	 */
	public function setStatus($status)
	{
		if (!in_array($status, self::$states))
		{
			throw new \InvalidArgumentException(sprintf('Status must be one of: %s.', implode(', ', self::$states)));
		}
		$this->status = $status;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}



	/**
	 * @return ArrayCollection
	 */
	public function getCategories()
	{
		return $this->categories;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
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
	 * @param mixed $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPrice()
	{
		return $this->price;
	}


	/**
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("image_url")
	 * @return string
	 */
	public function getImageUrl()
	{
		return sprintf('http://placehold.it/300x200/%03X&text=%s', mt_rand(0, 0xF), urlencode($this->getId()));
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

}