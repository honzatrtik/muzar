<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemRepository;
use DoctrineExtensions\NestedSet;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\FormType\ItemFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(service="muzar_bazaar.controller.form_item")
 */
class ItemFormController
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
	 * @Route("/ads/edit/{id}", name="muzar_bazaar_item_form_default")
	 * @Method("GET")
	 * @Rest\View()
	 */
	public function defaultAction(Request $request, $id)
	{

		$categories = $this->itemService->getSelectableCategories();
		$form = $this->formFactory->createNamedBuilder(NULL, new ItemFormType($categories), $item)->getForm();
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
