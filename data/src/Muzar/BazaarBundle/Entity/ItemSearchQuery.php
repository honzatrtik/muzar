<?php
/**
 * Date: 28/04/14
 * Time: 17:35
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Muzar\BazaarBundle\Elastica\QueryFactory;
use Symfony\Component\Validator\Constraints as Assert;
use Elastica\Filter\AbstractFilter;
use Elastica\Filter\BoolAnd;
use Elastica\Filter\Nested;
use Elastica\Filter\Prefix;
use Elastica\Filter\Terms;
use Elastica\Filter\Type;
use Elastica\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_search_query", indexes={@ORM\Index(name="item_search_query_user_id_hash_idx",columns={"user_id","hash"}),@ORM\Index(name="item_search_query_category_str_id_idx",columns={"category_str_id"})})
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
	 */
	protected $id;


	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $query;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $categoryStrId;

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
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $region;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $district;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $hash;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
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
			->setDistrict($request->query->get('district'))
			->setRegion($request->query->get('region'))
			->setQuery($request->query->get('query'))
			->setPriceFrom($request->query->get('priceFrom'))
			->setPriceTo($request->query->get('priceTo'))
			->setCategoryStrId($request->query->get('category'));

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
	 * @return mixed
	 */
	public function getCategoryStrId()
	{
		return $this->categoryStrId;
	}

	/**
	 * @param mixed $categoryStrId
	 */
	public function setCategoryStrId($categoryStrId)
	{
		$this->categoryStrId = $categoryStrId;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDistrict()
	{
		return $this->district;
	}

	/**
	 * @param mixed $district
	 */
	public function setDistrict($district)
	{
		$this->district = $district;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @param mixed $region
	 */
	public function setRegion($region)
	{
		$this->region = $region;
		return $this;
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
			'categoryStrId' => $this->categoryStrId,
			'priceFrom' => $this->priceFrom,
			'priceTo' => $this->priceTo,
			'district' => $this->district,
			'region' => $this->region
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

	/**
	 * @return Query
	 */
	public function createElasticaQuery()
	{
		$created = new Query\Filtered();

		$filters = new \Elastica\Filter\BoolAnd();
		$filters->addFilter(new Type('item'));

		$range = array();
		$range['gte'] = $this->getPriceFrom();
		$range['lte'] = $this->getPriceTo();

		if ($range = array_filter($range))
		{
			$filters->addFilter(new \Elastica\Filter\Range('price', $range));
		}

		if ($categoryStrId = $this->getCategoryStrId())
		{
			$filters->addFilter(new Terms('category_str_ids', array($categoryStrId)));
		}

		$locationQuery = array();
		if ($district = $this->getDistrict())
		{
			$locationQuery['contact.district'] = $district;
		}

		if ($region = $this->getRegion())
		{
			$locationQuery['contact.region'] = $region;
		}

		if (count($locationQuery))
		{

			$q = [];
			array_walk($locationQuery, function($string, $field) use (&$q) {
				$q[] = "$field:$string";
			});

			$locationQuery = new Query\QueryString(join(' AND ', $q));
			$locationQuery->setParam('analyzer', 'ascii_folding_geo');
			$filters->addFilter(new \Elastica\Filter\Query($locationQuery));
		}


		$query = $this->getQuery();

		$created->setQuery($query ? new Query\QueryString($query) : new Query\MatchAll());
		$created->setFilter($filters);

		return new Query($created);
	}


	/** @ORM\PrePersist */
	public function createHashOnPrePersist()
	{
		$this->setHash($this->createHash());
	}
}