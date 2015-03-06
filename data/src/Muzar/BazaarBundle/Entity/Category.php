<?php
/**
 * Date: 20/01/14
 * Time: 14:12
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\NestedSet\Node;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="category",indexes={@ORM\Index(name="category_str_id_idx",columns={"str_id"})})
 * @ORM\EntityListeners({"Muzar\BazaarBundle\Entity\CategoryListener"})
 * @ORM\HasLifecycleCallbacks
 *
 * @JMS\ExclusionPolicy("all")
 */
class Category implements Node
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 *
	 * @JMS\Expose()
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $lft;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $rgt;

	/**
	 * @ORM\Column(type="string", length=128)
	 *
	 * @JMS\Expose()
	 */
	private $strId;


	/**
	 * @ORM\Column(type="string", length=128)
	 *
	 * @JMS\Expose()
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @var string
	 */
	private $path;

	/**
	 * @var Category[]
	 */
	private $ancestors;

	function __construct()
	{
	}


	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function getLeftValue()
	{
		return $this->lft;
	}

	public function setLeftValue($lft)
	{
		$this->lft = $lft;
		return $this;
	}

	public function getRightValue()
	{
		return $this->rgt;
	}

	public function setRightValue($rgt)
	{
		$this->rgt = $rgt;
		return $this;
	}

	/**
	 * @param mixed $strId
	 */
	public function setStrId($strId)
	{
		$this->strId = $strId;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getStrId()
	{
		return $this->strId;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @JMS\VirtualProperty
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param Category[] $ancestors
	 */
	public function setAncestors($ancestors)
	{
		$this->ancestors = $ancestors;
		return $this;
	}

	/**
	 * @return Category[]
	 */
	public function getAncestors()
	{
		return $this->ancestors;
	}

	/**
	 * @JMS\VirtualProperty
	 * @return int
	 */
	public function getDepth()
	{
		return count($this->getAncestors());
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function __toString()
	{
		return $this->name;
	}

}