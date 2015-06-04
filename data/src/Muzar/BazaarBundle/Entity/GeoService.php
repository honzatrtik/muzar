<?php
/**
 * Date: 24/02/14
 * Time: 13:48
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\EntityManager;
use FOS\ElasticaBundle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GeoService
{

	/** @var EntityManager */
	protected $em;

	function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function getRegionsAndDistricts()
	{
		$query = $this->em->getRepository('Muzar\BazaarBundle\Entity\Region')
			->createQueryBuilder('r')
			->select('r', 'd')
			->join('r.districts', 'd')
			->getQuery();

		$query->useResultCache(TRUE, 60, 'regionsAndDistricts');
		return $query->getResult();
	}

} 