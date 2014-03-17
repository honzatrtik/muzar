<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass="Muzar\BazaarBundle\Entity\ItemRepository")
 * @ORM\Table(name="item", indexes={@ORM\Index(name="item_status_idx",columns={"status"})})
 * @ORM\HasLifecycleCallbacks
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
	 * @Assert\NotBlank()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 *
	 */
	protected $description;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
	 * @JMS\Expose()
	 */
	protected $price;

	/**
	 * @ORM\Column(type="boolean")
	 * @JMS\Expose()
	 */
	protected $negotiablePrice = FALSE;

	/**
	 * @ORM\Column(type="boolean")
	 * @JMS\Expose()
	 */
	protected $allowSendingByMail = FALSE;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 * @JMS\Type("DateTime")
	 * @JMS\Expose()
	 */
	protected $created;

	/**
	 * @var Category
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 **/
	protected  $category;

	/**
	 * @var Contact
	 * @ORM\ManyToOne(targetEntity="Contact", cascade={"persist"})
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 **/
	private $contact;

	function __construct()
	{
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
	 * @param Category $category
	 */
	public function setCategory(Category $category)
	{
		$this->category = $category;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCategory()
	{
		return $this->category;
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
	 * @param boolean $allowSendingByMail
	 */
	public function setAllowSendingByMail($allowSendingByMail)
	{
		$this->allowSendingByMail = $allowSendingByMail;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getAllowSendingByMail()
	{
		return $this->allowSendingByMail;
	}

	/**
	 * @param boolean $negotiablePrice
	 */
	public function setNegotiablePrice($negotiablePrice)
	{
		$this->negotiablePrice = $negotiablePrice;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getNegotiablePrice()
	{
		return $this->negotiablePrice;
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

	/**
	 * @param Contact $contact
	 */
	public function setContact(Contact $contact)
	{
		$this->contact = $contact;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getContact()
	{
		return $this->contact;
	}



	/** @ORM\PrePersist */
	public function setCreatedOnPrePersist()
	{
		if (!$this->getId() && !$this->getCreated())
		{
			$this->setCreated(new \DateTime());
		}
	}

	/**
	 * @Assert\Callback
	 */
	public function validatePrice(ExecutionContextInterface $context)
	{
		$price = $this->getPrice();
		$valid = $this->getNegotiablePrice()
			|| (is_numeric($price) && $price > 0);

		if (!$valid)
		{
			$context->addViolationAt('price', 'Cena musí být kladné celé číslo nebo dohodou.');
		}
	}


}