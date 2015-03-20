<?php
/**
 * Date: 28/04/14
 * Time: 17:35
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Elastica\Query;
use Elastica\Query\Prefix;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\Request;


class CategorySearchQuery
{

	protected $query;


	/**
	 * @param Request $request
	 * @return CategorySearchQuery
	 */
	public static function createFromRequest(Request $request)
	{
		/** @var ItemSearchQuery $categorySearchQuery */
		$categorySearchQuery = new static();
		$categorySearchQuery
			->setQuery($request->query->getAlnum('query'));

		return $categorySearchQuery;
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
	 * Vypocita checksum query
	 * @return string
	 */
	public function createHash()
	{
		return sha1(json_encode(array(
			$this->query,
		)));
	}

	public function isFilled()
	{
		return (boolean) $this->getQuery();
	}

	/**
	 * @return Query
	 */
	public function createElasticaQuery()
	{
		if (!$this->isFilled())
		{
			throw new \RuntimeException('Must be filled!');
		}

		$query = $this->getQuery();

		/**
		 * See http://www.elasticsearch.org/guide/en/elasticsearch/guide/current/_query_time_search_as_you_type.html
		 */
		$prefix = new Prefix();
		$prefix->setPrefix('path', $query);

		return new Query($prefix);
	}



}