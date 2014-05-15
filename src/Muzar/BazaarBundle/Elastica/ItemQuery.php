<?php
/**
 * Date: 14/05/14
 * Time: 17:28
 */

namespace Muzar\BazaarBundle\Elastica;

use Elastica\Filter\AbstractFilter;
use Elastica\Filter\BoolAnd;
use Elastica\Filter\Type;
use Elastica\Query;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;

class ItemQuery extends Query
{
	/**
	 * @var BoolAnd
	 */
	protected $filters;


	function __construct(ItemSearchQuery $itemSearchQuery)
	{
		if (!$itemSearchQuery->isFilled())
		{
			throw new \InvalidArgumentException('ItemSearchQuery must be filled!');
		}

		$query = $itemSearchQuery->getQuery();
		parent::__construct($query ? new Query\QueryString($query) : new Query\MatchAll());

		$this->filters = new \Elastica\Filter\BoolAnd();

		$range = array();
		$range['gte'] = $itemSearchQuery->getPriceFrom();
		$range['lte'] = $itemSearchQuery->getPriceTo();

		$this->filters->addFilter(new Type('item')); // Mame alespon jednu podminku pro BoolAnd
		if ($range = array_filter($range))
		{
			$this->filters->addFilter(new \Elastica\Filter\Range('price', $range));
		}

		$this->setFilter($this->filters);
	}

	public static function create($query)
	{
		if ($query instanceof ItemSearchQuery)
		{
			return new static($query);
		}
		return parent::create($query);
	}


	public function addFilter(AbstractFilter $filter)
	{
		$this->filters->addFilter($filter);
		$this->setFilter($this->filters);
		return $this;
	}
} 