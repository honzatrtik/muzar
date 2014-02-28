<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\ItemRepository;
use DoctrineExtensions\NestedSet;
use Muzar\BazaarBundle\Entity\ItemService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(service="muzar_bazaar.controller.item")
 */
class ItemController
{

	/** @var  Router */
	protected $router;

	/** @var  ItemService */
	protected $service;

	public function __construct(Router $router, ItemService $service)
	{
		$this->router = $router;
		$this->service = $service;
	}

	/**
	 * @Route("/ads/{id}", name="muzar_bazaar_item_get")
	 * @Rest\View
	 */
	public function getAction(Request $request, $id)
	{
		return $this->service->getItem($id);
	}


	/**
	 * @Route("/ads", name="muzar_bazaar_item_all")
	 * @Rest\View
	 */
	public function allAction(Request $request)
    {

		$maxResults = $request->query->get('limit', ItemService::DEFAULT_MAX_RESULTS);
		$categoryStrId = $request->query->get('category', ItemService::DEFAULT_CATEGORY_STR_ID);
		$query = $request->query->get('query');
		$startId = $request->query->get('startId');

		if ($query && is_string($query))
		{
			$result = $this->service->getItemsFulltext($query, $maxResults + 1, $startId);
			$total = $this->service->getItemsFulltextTotal($categoryStrId);
		}
		else
		{
			$result = $this->service->getItems($categoryStrId, $maxResults + 1, $startId);
			$total = $this->service->getItemsTotal($categoryStrId);
		}

		if (count($result) === ($maxResults + 1))
		{
			$lastItem = array_pop($result);
			$nextLink = $this->router->generate('muzar_bazaar_item_all', array(
				'startId' => $lastItem->getId(),
				'limit' => $maxResults,
				'category' => $request->query->get('category'),
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
