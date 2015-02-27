<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\CategorySearchQuery;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Suggestion\QuerySuggesterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route(service="muzar_bazaar.controller.suggestion")
 */
class SuggestionController
{
	const CACHE_LIFETIME  = 120;

	/** @var \Doctrine\Common\Cache\Cache */
	protected $cache;

	/** @var  Router */
	protected $router;

	/** @var  CategoryService */
	protected $categoryService;

	/** @var  QuerySuggesterInterface  */
	protected $suggester;

	public function __construct(Router $router, CategoryService $categoryService, QuerySuggesterInterface $suggester)
	{
		$this->router = $router;
		$this->categoryService = $categoryService;
		$this->suggester = $suggester;
	}

	/**
	 * @param \Doctrine\Common\Cache\Cache $cache
	 */
	public function setCache(\Doctrine\Common\Cache\Cache $cache)
	{
		$this->cache = $cache;
		return $this;
	}


	/**
	 * @Route("/suggestions", name="muzar_bazaar_suggestion_all")
	 * @Rest\View
	 */
	public function allAction(Request $request)
    {

		$searchQuery = CategorySearchQuery::createFromRequest($request);
		if (!$searchQuery->isFilled())
		{
			throw new HttpException(400, 'Empty search query.');
		}

		$categories = $this->getCategories($searchQuery);
		$queries = $this->getQueries($searchQuery);

		return array(
			'meta' => array(),
			'data' => array(
				'queries' => $queries,
				'ads' => array(),
				'categories' => $categories,
			)
		);
    }

	protected function getQueries(CategorySearchQuery $searchQuery)
	{
		if ($this->cache)
		{
			$key = __METHOD__ . '.' . $searchQuery->createHash();
			if (!$queries = $this->cache->fetch($key))
			{
				$queries = $this->suggester->get($searchQuery->getQuery(), 5);
				$this->cache->save($key, $queries, self::CACHE_LIFETIME);
			}
			return $queries;
		}
		else
		{
			return $this->suggester->get($searchQuery->getQuery(), 5);
		}
	}

	protected function getCategories(CategorySearchQuery $searchQuery)
	{
		if ($this->cache)
		{
			$key = __METHOD__ . '.' . $searchQuery->createHash();
			if (!$categories = $this->cache->fetch($key))
			{
				$categories = $this->categoryService->getCategoriesFulltext($searchQuery, 5);
				$this->cache->save($key, $categories, self::CACHE_LIFETIME);
			}
			return $categories;
		}
		else
		{
			return $this->categoryService->getCategoriesFulltext($searchQuery, 5);
		}
	}



}
