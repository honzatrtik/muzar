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
 * @Route(service="muzar_bazaar.controller.category")
 */
class CategoryController
{

	/** @var  Router */
	protected $router;

	/** @var  EntityManager */
	protected $em;

	/** @var  CategoryService */
	protected $categoryService;

	public function __construct(Router $router, EntityManager $em, CategoryService $categoryService)
	{
		$this->router = $router;
		$this->em = $em;
		$this->categoryService = $categoryService;
	}

	/**
	 * @Route("/categories", name="muzar_bazaar_category_all")
	 * @Rest\View
	 */
	public function allAction(Request $request)
    {
		return array(
			'meta' => array(),
			'data' => $this->categoryService->getTree(),
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
