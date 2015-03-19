<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="Muzar\BazaarBundle\Entity\ItemRepository")
 * @ORM\Table(name="item",indexes={@ORM\Index(name="item_status_idx",columns={"status"}), @ORM\Index(name="sluq_idx", columns={"slug"})})
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
	 * @JMS\Groups({"elastica"})
	 *
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose()
	 * @JMS\Groups({"elastica"})
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", length=32)
	 * @JMS\Expose()
	 * @JMS\Groups({"elastica"})
	 */
	protected $status = self::STATUS_ACTIVE;

	/**
	 * @ORM\Column(type="string", length=1024)
	 * @JMS\Expose()
	 * @JMS\Groups({"elastica"})
	 * @Assert\NotBlank()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 * @JMS\Groups({"elastica"})
	 *
	 */
	protected $description;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
	 * @JMS\Expose()
	 * @JMS\Groups({"elastica"})
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
	 * @JMS\Groups({"elastica"})
	 */
	protected $created;

	/**
	 * @var Category
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
	 * @ORM\JoinColumn(nullable=false)
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 * @Assert\Valid()
	 **/
	protected $category;

	/**
	 * @var Contact
	 * @ORM\ManyToOne(targetEntity="Contact", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 * @Assert\Valid()
	 **/
	private $contact;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
	 * @JMS\Expose()
	 * @Assert\Valid()
	 * //@Assert\NotBlank()
	 **/
	private $user;




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
	public function setCategory(Category $category = NULL)
	{
		$this->category = $category;
		return $this;
	}

	/**
	 * @return Category
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
		return sprintf('https://placeimg.com/300/200/people/grayscale?%d', $this->getId());
	}

	/**
	 * @JMS\VirtualProperty
	 * @JMS\SerializedName("category_str_ids")
	 * @JMS\Groups({"elastica"})
	 * @return array
	 */
	public function getCategoryStrIds()
	{

		if ($category = $this->getCategory())
		{
			$categories = array_map(function(Category $c) {
				return $c->getStrId();
			}, $category->getAncestors());
			array_push($categories, $category->getStrId());
			return $categories;
		}
		else
		{
			return array();
		}
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
	 * @return mixed
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param mixed $slug
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
		return $this;
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

	/**
	 * @param \Muzar\BazaarBundle\Entity\User $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return \Muzar\BazaarBundle\Entity\User
	 */
	public function getUser()
	{
		return $this->user;
	}


	/** @ORM\PrePersist */
	public function setCreatedOnPrePersist()
	{
		if (!$this->getId() && !$this->getCreated())
		{
			$this->setCreated(new \DateTime());
		}
		return $this;
	}

	/** @ORM\PrePersist */
	public function setSlugOnPrePersist()
	{
		if (!$this->getId() && !$this->getSlug())
		{
			$slugify = new Slugify();
			$this->setSlug(trim($slugify->slugify($this->getName())));
		}
		return $this;
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
			$context->buildViolation('Cena musí být kladné celé číslo nebo dohodou.')->atPath('price')->addViolation();
		}
	}

	/**
	 * @Assert\Callback
	 */
	public function validateCategory(ExecutionContextInterface $context)
	{
		if ($category = $this->getCategory())
		{
			if ($category->getDepth() < 1)
			{
				$context
					->buildViolation(sprintf('Musíte upřesnit kategorii, vyberte nějakou podkategorii kategorie "%s".', $category->getName()))
					->atPath('category')
					->addViolation();
			}
		}
	}

}