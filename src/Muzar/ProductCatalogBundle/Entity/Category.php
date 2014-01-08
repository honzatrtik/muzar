<?php

namespace Muzar\ProductCatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(
 * 		name="category",
 *		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="category_name_UQ",columns={"name"})
 * 		}
 * )
 * @ORM\Entity(
 * 		repositoryClass="Muzar\ProductCatalogBundle\Entity\CategoryRepository"
 * )
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Product", mappedBy="categories")
	 **/
	private $products;

	function __construct()
	{
		$this->products =  new ArrayCollection();
	}

	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

	/**
	 * @return ArrayCollection
	 */
	public function getProducts()
	{
		return $this->products;
	}

	function __toString()
	{
		return $this->getName();
	}


}
