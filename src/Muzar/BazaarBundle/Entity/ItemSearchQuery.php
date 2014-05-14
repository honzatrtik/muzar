<?php
/**
 * Date: 28/04/14
 * Time: 17:35
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_search_query")
 * @ORM\HasLifecycleCallbacks
 *
 * @JMS\ExclusionPolicy("all")
 */
class ItemSearchQuery
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose()
	 */
	protected $id;


	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $query;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
	 * @JMS\Expose()
	 */
	protected $priceFrom;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
	 * @JMS\Expose()
	 */
	protected $priceTo;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 * @JMS\Expose()
	 * @Assert\NotBlank()
	 **/
	protected  $user;


	/**
	 * @param Request $request
	 * @return ItemSearchQuery
	 */
	public static function createFromRequest(Request $request)
	{
		/** @var ItemSearchQuery $holder */
		$holder = new static();
		$holder
			->setQuery($request->query->getAlnum('query'))
			->setPriceFrom($request->query->get('priceFrom'))
			->setPriceTo($request->query->get('priceTo'));

		return $holder;
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
	 * @param mixed $priceFrom
	 */
	public function setPriceFrom($priceFrom)
	{
		$this->priceFrom = $priceFrom;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPriceFrom()
	{
		return $this->priceFrom;
	}

	/**
	 * @param mixed $priceTo
	 */
	public function setPriceTo($priceTo)
	{
		$this->priceTo = $priceTo;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPriceTo()
	{
		return $this->priceTo;
	}

	/**
	 * @param mixed $query
	 */
	public function setQuery($query)
	{
		$this->query = $query;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getQuery()
	{
		return $this->query;
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


	public function isFilled()
	{
		return $this->getQuery()
			|| $this->getPriceFrom()
			|| $this->getPriceTo();
	}


}