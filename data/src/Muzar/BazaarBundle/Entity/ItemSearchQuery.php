<?php
/**
 * Date: 28/04/14
 * Time: 17:35
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Muzar\BazaarBundle\Elastica\ItemQuery;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_search_query", indexes={@ORM\Index(name="item_search_query_user_id_hash_idx",columns={"user_id","hash"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({"Muzar\BazaarBundle\Entity\ItemSearchQueryListener"})
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
	 * @ORM\Column(type="string")
	 * @JMS\Expose()
	 */
	protected $hash;


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
		/** @var ItemSearchQuery $itemSearchQuery */
		$itemSearchQuery = new static();
		$itemSearchQuery
			->setQuery($request->query->getAlnum('query'))
			->setPriceFrom($request->query->get('priceFrom'))
			->setPriceTo($request->query->get('priceTo'));

		return $itemSearchQuery;
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

	/**
	 * @return mixed
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * @param mixed $hash
	 */
	public function setHash($hash)
	{
		$this->hash = $hash;
		return $this;
	}

	public function toArray()
	{
		return array(
			'query' => $this->query,
			'priceFrom' => $this->priceFrom,
			'priceTo' => $this->priceTo,
		);
	}

	/**
	 * Vypocita checksum query
	 * @return string
	 */
	public function createHash()
	{
		return sha1(json_encode($this->toArray()));
	}


	public function isFilled()
	{
		return $this->getQuery()
			|| $this->getPriceFrom()
			|| $this->getPriceTo();
	}

	/**
	 * @return ItemQuery
	 */
	public function getElasticaQuery()
	{
		if (!$this->isFilled())
		{
			throw new \RuntimeException('Must be filled!');
		}
		return new ItemQuery($this);
	}


	/** @ORM\PrePersist */
	public function createHashOnPrePersist()
	{
		$this->setHash($this->createHash());
	}
}