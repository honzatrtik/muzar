<?php

namespace Muzar\ProductCatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(
 * 		name="product",
 *		indexes={
 * 			@ORM\Index(name="product_source_IDX", columns={"source"}),
 * 			@ORM\Index(name="product_ean_IDX", columns={"ean"})
 * 		}
 * )
 * @ORM\Entity(
 * 		repositoryClass="Muzar\ProductCatalogBundle\Entity\ProductRepository"
 * )
 */
class Product
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
	 * ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
	 **/
	private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=128)
     */
    private $source;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ean", type="string", length=32, nullable=true)
	 */
	private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="imageUrl", type="text", nullable=true)
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    private $price;


	function __construct()
	{
		$this->categories = new ArrayCollection();
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
     * Set source
     *
     * @param string $source
     * @return Product
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }


    /**
     * Set productName
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }



    /**
     * Set url
     *
     * @param string $url
     * @return Product
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return Product
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string 
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

	/**
	 * @param string
	 */
	public function setEan($ean)
	{
		$this->ean = $ean;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEan()
	{
		return $this->ean;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getCategories()
	{
		return $this->categories;
	}


	function __toString()
	{
		return $this->getName();
	}




}
