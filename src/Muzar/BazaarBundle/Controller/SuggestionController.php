<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="muzar_bazaar.controller.suggestion")
 */
class SuggestionController
{

	/** @var  Router */
	protected $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	/**
	 * @Route("/suggestions", name="muzar_bazaar_suggestion_all")
	 * @Rest\View
	 */
	public function allAction(Request $request)
    {
		return array(
			'meta' => array(),
			'data' => array(
				'ads' => array(),
				'categories' => array(),
			)
		);
    }

}
