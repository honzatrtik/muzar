<?php
/**
 * Date: 26/11/13
 * Time: 18:35
 */

namespace Muzar\ScraperBundle\Entity;


use Doctrine\ORM\EntityRepository;

class AdRepository  extends EntityRepository
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