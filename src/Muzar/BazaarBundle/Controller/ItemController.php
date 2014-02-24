<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="muzar_bazaar.controller.item")
 */
class ItemController
{

	const DEFAULT_LIMIT = 64;

	/** @var  Router */
	protected $router;

	/** @var  EntityManager */
	protected $em;

	public function __construct(Router $router, EntityManager $em)
	{
		$this->router = $router;
		$this->em = $em;
	}

	/**
	 * @Route("/ads", name="muzar_bazaar_item_all")
	 * @Rest\View
	 */
	public function allAction(Request $request)
    {

		$limit = $request->query->get('limit', self::DEFAULT_LIMIT);
		$category = $request->query->get('category');
		$startId = $request->query->get('startId');

		$repository = $this->em->getRepository('Muzar\BazaarBundle\Entity\Item');

		$params = array();
		$builder = $repository->createQueryBuilder('i');
		if ($category)
		{
			$builder->join('i.categories', 'c')->where('c.name', $category); // TODO prepsat
		}

		$builderTotal = clone $builder;
		$total = $builderTotal->select('COUNT(i.id)')->getQuery()->getSingleScalarResult();

		// Nacteme o jeden prvek vic, abychom zjistili, zda mame dalsi stranku
		$builder
			->setMaxResults($limit + 1)
			->addOrderBy('i.id', 'DESC');



		if ($startId)
		{
			$builder->where('i.id <= :startId');
			$params['startId'] = $startId;
		}

		$result = $builder->getQuery()->setParameters($params)->getResult();

		if (count($result) === ($limit + 1))
		{
			$lastItem = array_pop($result);
			$nextLink = $this->router->generate('muzar_bazaar_item_all', array(
				'startId' => $lastItem->getId(),
				'limit' => $limit,
			));
		}
		else
		{
			$nextLink = NULL;
		}


		return array(
			'data' => $result,
			'meta' => array(
				'total' => $total,
				'nextLink' => $nextLink
			),
		);

    }

}
