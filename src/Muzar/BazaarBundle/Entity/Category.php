<?php
/**
 * Date: 20/01/14
 * Time: 14:12
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\NestedSet\Node;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="category",indexes={@ORM\Index(name="category_str_id_idx",columns={"str_id"})})
 */
class Category implements Node
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
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
	 */
	private $strId;


	/**
	 * @ORM\Column(type="string", length=128)
	 */
	private $name;

	/**
	 * ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Item", mappedBy="categories")
	 **/
	private $items;

	function __construct()
	{
		$this->items = new ArrayCollection();
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