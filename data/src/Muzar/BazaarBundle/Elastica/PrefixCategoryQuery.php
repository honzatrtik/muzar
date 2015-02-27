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
use Muzar\BazaarBundle\Entity\CategorySearchQuery;

class PrefixCategoryQuery extends Query
{

	function __construct(CategorySearchQuery $categorySearchQuery)
	{
		if (!$categorySearchQuery->isFilled())
		{
			throw new \InvalidArgumentException('CategorySearchQuery must be filled!');
		}

		$query = $categorySearchQuery->getQuery();

		/**
		 * See http://www.elasticsearch.org/guide/en/elasticsearch/guide/current/_query_time_search_as_you_type.html
		 */
		$q = new Query\Prefix();
		$q->setPrefix('path', $query);

		parent::__construct($q);
	}

} 