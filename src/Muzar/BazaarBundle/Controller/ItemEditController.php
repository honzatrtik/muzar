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
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ItemEditController
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
	 * @Rest\View()
	 */
	public function defaultAction(Request $request, $id)
	{

		$categories = $this->itemService->getSelectableCategories();
		$item = $id
			? $this->itemService->getItem($id)
			: new Item();

		$form = $this->formFactory->createNamedBuilder(NULL, new ItemType($categories), $item)->getForm();
		$form->handleRequest($request);

		if ($form->isValid())
		{
			$this->em->persist($item);
			$this->em->flush();

			return new RedirectResponse($this->router->generate('muzar_bazaar_item_form_default'));
		}
		else
		{
			return $form;
		}
	}


}
