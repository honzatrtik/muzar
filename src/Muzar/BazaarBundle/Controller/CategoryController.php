<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="muzar_bazaar.controller.category")
 */
class CategoryController
{

	/** @var  Router */
	protected $router;

	/** @var  EntityManager */
	protected $em;

	/** @var  NestedSet\Manager */
	protected $nsm;

	public function __construct(Router $router, EntityManager $em, NestedSet\Manager $nsm)
	{
		$this->router = $router;
		$this->em = $em;
		$this->nsm = $nsm;
	}

	/**
	 * @Route("/categories", name="muzar_bazaar_category_all")
	 * @Rest\View
	 */
	public function allAction(Request $request)
    {
		$data = $this->createBranch($this->nsm->fetchTree());

		return array(
			'meta' => array(),
			'data' => $data,
		);
    }

	protected function createBranch(NestedSet\NodeWrapper $wrapper)
	{
		$data = array();
		foreach($wrapper->getChildren() as $childWrapper)
		{
			/** @var NestedSet\NodeWrapper $childWrapper */

			/** @var Category $category */

			$category = $childWrapper->getNode();

			$data[] = array(
				'strId' => $category->getStrId(),
				'name' => $category->getName(),
				'children' => $this->createBranch($childWrapper),
			);
		}
		return $data;
	}


}
