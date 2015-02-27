<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose()
	 */
	protected $id;

	/**
	 * ArrayCollection
	 * @ORM\OneToMany(targetEntity="ItemSearchQuery", mappedBy="user")
	 * @JMS\Expose()
	 **/
	protected $itemSearchQueries;

	/**
	 * ArrayCollection
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="user")
	 **/
	protected $items;

	public function __construct()
	{
		parent::__construct();
		$this->items = new ArrayCollection();
		$this->itemSearchQueries = new ArrayCollection();
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return ArrayCollection
	 */
	public function getItemSearchQueries()
	{
		return $this->itemSearchQueries;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getItems()
	{
		return $this->items;
	}


	public function getGravatarEmailHash()
	{
		return md5(strtolower(trim($this->getEmail())));
	}
}