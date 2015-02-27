<?php
/**
 * Date: 28/04/14
 * Time: 17:35
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Muzar\BazaarBundle\Elastica\PrefixCategoryQuery;
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
	 * @return PrefixCategoryQuery
	 */
	public function getElasticaQuery()
	{
		if (!$this->isFilled())
		{
			throw new \RuntimeException('Must be filled!');
		}
		return new PrefixCategoryQuery($this);
	}



}