<?php
/**
 * Date: 25/11/13
 * Time: 17:14
 */

namespace Muzar\BazaarBundle\Entity;


use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
	public function createStatusActiveQueryBuilder($alias = 'i')
	{
		return $this->createQueryBuilder($alias)
			->where($alias . '.status = :status')
			->setParameter('status', Item::STATUS_ACTIVE);
	}
}