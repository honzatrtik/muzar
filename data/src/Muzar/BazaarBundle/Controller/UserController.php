<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManager;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemRepository;
use DoctrineExtensions\NestedSet;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Entity\User;
use Muzar\BazaarBundle\Form\ItemType;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @Route(service="muzar_bazaar.controller.user")
 */
class UserController
{

	/** @var  Router */
	protected $router;

	/** @var  ItemService */
	protected $userManager;

	/** @var  EntityManager */
	protected $em;

	/** @var  FormFactory */
	protected $formFactory;

	/** @var  SecurityContextInterface */
	protected $securityContext;

	public function __construct(
		Router $router,
		FormFactory $formFactory,
		EntityManager $em,
		UserManager $userManager,
		SecurityContextInterface $securityContext
	)
	{
		$this->router = $router;
		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->userManager = $userManager;
		$this->securityContext = $securityContext;
	}

	/**
	 * @Route("/users/current", name="muzar_bazaar_user_get")
	 * @Method("GET")
	 * @Rest\View()
	 * @Security("has_role('ROLE_USER')")
	 */
	public function getAction(Request $request)
	{
		return $this->getUser();
	}


	/**
	 * @Route("/users/current/watchdog", name="muzar_bazaar_user_get_item_search_queries")
	 * @Method("GET")
	 * @Rest\View()
	 * @Security("has_role('ROLE_USER')")
	 */
	public function getSearchItemQueriesAction(Request $request)
	{
		$user = $this->getUser();
		return array(
			'meta' => array(),
			'data' => $user->getItemSearchQueries(),
		);
	}

	/**
	 * @Route("/users/current/watchdog", name="muzar_bazaar_user_post_item_search_queries")
	 * @Method("POST")
	 * @Rest\View()
	 * @Security("has_role('ROLE_USER')")
	 */
	public function postSearchItemQueriesAction(Request $request)
	{
		$user = $this->getUser();
		return array(
			'meta' => array(),
			'data' => $user->getItemSearchQueries(),
		);
	}

	/**
	 * @return User
	 */
	protected function getUser()
	{
		return $this->securityContext->getToken()->getUser();
	}
}

