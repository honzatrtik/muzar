<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemRepository;
use DoctrineExtensions\NestedSet;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Form\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
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
	protected $itemService;

	/** @var  EntityManager */
	protected $em;

	/** @var  FormFactory */
	protected $formFactory;


	public function __construct(
		Router $router,
		FormFactory $formFactory,
		EntityManager $em,
		ItemService $itemService
	)
	{
		$this->router = $router;
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->itemService = $itemService;
	}

	/**
	 * @Route("/ads/{id}", name="muzar_bazaar_item_get", requirements={"id" = "\d+"})
	 * @Method("GET")
	 * @Rest\View()
	 */
	public function getAction(Request $request, $id)
	{
		return $this->itemService->getItem($id);
	}

	/**
	 * @Route("/ads", name="muzar_bazaar_item_post")
	 * @Method("POST")
	 * @Rest\View(statusCode=201)
	 */
	public function postAction(Request $request)
	{
		return $this->processForm(new Item(), $request);
	}

	/**
	 * @Route("/ads/{id}", name="muzar_bazaar_item_put", requirements={"id" = "\d+"})
	 * @Method("PUT")
	 * @Rest\View()
	 */
	public function putAction(Request $request, $id)
	{
		$item = $this->itemService->getItem($id);
		return $this->processForm($item, $request);
	}


	/**
	 * @Route("/ads", name="muzar_bazaar_item_all")
	 * @Method("GET")
	 * @Rest\View()
	 */
	public function allAction(Request $request)
    {

		$maxResults = $request->query->get('limit', ItemService::DEFAULT_MAX_RESULTS);
		$categoryStrId = $request->query->get('category', ItemService::DEFAULT_CATEGORY_STR_ID);
		$query = $request->query->get('query');
		$startId = $request->query->get('startId');

		if ($query && is_string($query))
		{
			$result = $this->itemService->getItemsFulltext($query, $maxResults + 1, $startId);
			$total = $this->itemService->getItemsFulltextTotal($categoryStrId);
		}
		else
		{
			$result = $this->itemService->getItems($categoryStrId, $maxResults + 1, $startId);
			$total = $this->itemService->getItemsTotal($categoryStrId);
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


	protected function getForm(Item $item, $method = 'POST')
	{
		$categories = $this->itemService->getSelectableCategories();
		return $this->formFactory->createNamedBuilder(NULL, new ItemType($categories), $item, array(
			'method' => $method
		))->getForm();


	}

	protected function processForm(Item $item, Request $request)
	{

		$form = $this->getForm($item, $request->getMethod());
		$form->handleRequest($request);

		if ($form->isValid())
		{
			$this->em->persist($item);
			$this->em->flush();
			return $item;
		}
		else
		{
			return $form;
		}

	}


}

