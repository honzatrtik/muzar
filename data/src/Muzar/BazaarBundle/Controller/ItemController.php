<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ExceptionWrapperHandler;
use FOS\RestBundle\View\View;
use Muzar\BazaarBundle\Entity\Contact;
use Muzar\BazaarBundle\Entity\Item;
use DoctrineExtensions\NestedSet;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Entity\Utils;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Muzar\BazaarBundle\Suggestion\QuerySuggesterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;

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

	/** @var Utils  */
	protected $utils;

	/** @var  RecursiveValidator */
	protected $validator;

	/** @var  SecurityContextInterface */
	protected $securityContext;

	/** @var  QuerySuggesterInterface */
	protected $querySuggester;

	public function __construct(
		Router $router,
		RecursiveValidator $validator,
		EntityManager $em,
		Utils $utils,
		ItemService $itemService,
		QuerySuggesterInterface $querySuggester,
		TokenStorageInterface $securityContext
	)
	{
		$this->router = $router;
		$this->validator = $validator;
		$this->em = $em;
		$this->utils = $utils;
		$this->itemService = $itemService;
		$this->querySuggester = $querySuggester;
		$this->securityContext = $securityContext;
	}

	/**
	 * @Route("/ads/{id}", name="muzar_bazaar_item_get", requirements={"id" = "\d+"})
	 * @Method("GET")
	 * @Rest\View()
	 */
	public function getAction(Request $request, $id)
	{
		return array(
			'data' => $this->itemService->getItem($id),
			'meta' => new \stdClass(),
		);
	}

	/**
	 * @Route("/ads", name="muzar_bazaar_item_post")
	 * @Method({"POST","OPTIONS"})
	 * @Rest\View(statusCode=201)
	 *
	 */
	public function postAction(Request $request)
	{
		//@Security("has_role('ROLE_USER')")
		$item = new Item();
		$item->setContact(new Contact());

		//$item->setUser($this->securityContext->getToken()->getUser());
		return $this->processRequest($item, $request);
	}

	/**
	 * @Route("/ads/{id}", name="muzar_bazaar_item_put", requirements={"id" = "\d+"})
	 * @Method({"PUT","OPTIONS"})
	 * @Rest\View()
	 * @Security("has_role('ROLE_USER')")
	 */
	public function putAction(Request $request, $id)
	{
		$item = $this->itemService->getItem($id);
		if ($item->getUser()->getId() != $this->securityContext->getToken()->getUser()->getId())
		{
			throw new InsufficientAuthenticationException();
		}

		return $this->processRequest($item, $request);
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
		$startId = $request->query->get('startId');


		$searchQuery = ItemSearchQuery::createFromRequest($request);

		if ($query = $searchQuery->getQuery())
		{
			// Add search query to suggester index
			$this->querySuggester->add($query);
		}

		$result = $this->itemService->getItems($searchQuery, $maxResults + 1, $startId);
		$total = $this->itemService->getItemsTotal($searchQuery);


		if (count($result) === ($maxResults + 1))
		{
			$lastItem = array_pop($result);
			$nextLink = $this->router->generate('muzar_bazaar_item_all', array_merge($searchQuery->toArray(), array(
				'startId' => $lastItem->getId(),
				'limit' => $maxResults,
			)));
		}
		else
		{
			$nextLink = NULL;
		}

		return array(
			'data' => $result,
			'meta' => array(
				'total' => $total,
				'nextLink' => $nextLink,
				'query' => $searchQuery,
			),
		);

    }

	protected function transformRequest(Request $request)
	{
		if ($category = $request->request->get('category'))
		{
			$entity = $this->em->getRepository('Muzar\BazaarBundle\Entity\Category')
				->findOneBy(array('id' => $category));

			$request->request->set('category', $entity);
		}
	}

	protected function processRequest(Item $item, Request $request)
	{
		$this->transformRequest($request);
		$data = $request->request->all();
		$this->utils->fromArray($item, $data);

		$violations = $this->validator->validate($item);

		if (count($violations))
		{
			return View::create(array(
				'errors' => $violations,
			), Response::HTTP_BAD_REQUEST);
		}

		$this->em->persist($item);
		$this->em->flush();
		return array(
			'data' => $item,
			'meta' => new \stdClass(),
	);

	}


}

