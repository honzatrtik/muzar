<?php
/**
 * Date: 26/11/13
 * Time: 18:35
 */

namespace Muzar\ScraperBundle\Repository;


use Doctrine\ORM\EntityRepository;

class Ad  extends EntityRepository
{
	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getQueryUnparsed()
	{
		return $this->createQueryBuilder('a')
			->where('a.parsed IS NULL');
	}
} 