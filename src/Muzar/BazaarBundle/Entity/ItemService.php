<?php
/**
 * Date: 24/02/14
 * Time: 13:48
 */

namespace Muzar\BazaarBundle\Entity;

use DoctrineExtensions\NestedSet;
use Doctrine\ORM\EntityManager;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use FOS\ElasticaBundle;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemService 
{
	const DEFAULT_MAX_RESULTS = 60;
	const DEFAULT_CATEGORY_STR_ID = CategoryService::DEFAULT_CATEGORY_STR_ID;

	/** @var EntityManager */
	protected $em;


	/** @var  ElasticaBundle\Doctrine\RepositoryManager */
	protected $rm;

	/**
	 * @var CategoryService
	 */
	protected $categoryService;

	function __construct(EntityManager $em, ElasticaBundle\Manager\RepositoryManager $rm, CategoryService $cs)
	{
		$this->em = $em;
		$this->rm = $rm;
		$this->categoryService = $cs;
	}


	public function getSelectableCategories()
	{
		return $this->categoryService->getSelectableCategories();
	}

	public function getItem($id)
	{
		if (!$item = $this->em->getRepository('Muzar\BazaarBundle\Entity\Item')->find($id))
		{
			throw new NotFoundHttpException(sprintf('Item not found: %d.', $id));
		}
		return $item;
	}

	public function getItems($categoryStrId = CategoryService::DEFAULT_CATEGORY_STR_ID, $maxResults = self::DEFAULT_MAX_RESULTS, $startId = NULL)
	{
		$builder = $this->getItemsQueryBuilder($categoryStrId);
		if ($startId)
		{
			$builder->andWhere('i.id <= :startId')
				->setParameter('startId', $startId);
		}

		// Nacteme o jeden prvek vic, abychom zjistili, zda mame dalsi stranku
		$builder
			->setMaxResults($maxResults)
			->addOrderBy('i.id', 'DESC');

		return $builder->getQuery()->getResult();
	}


	public function getItemsTotal($categoryStrId = CategoryService::DEFAULT_CATEGORY_STR_ID)
	{
		return $this->getItemsQueryBuilder($categoryStrId)->select('COUNT(i.id)')->getQuery()->getSingleScalarResult();
	}


	protected function getItemsQueryBuilder($categoryStrId, $alias = 'i', $categoryAlias = 'c')
	{
		/** @var ItemRepository $repository */
		$repository = $this->em->getRepository('Muzar\BazaarBundle\Entity\Item');

		// Pridame sebe
		$categoryStrIds = array_map(function(Category $category) {
			return $category->getStrId();
		}, $this->categoryService->getDescendants($categoryStrId, TRUE));


		$builder = $repository->createStatusActiveQueryBuilder($alias);
		return $builder
			->join($alias . '.category', $categoryAlias)
			->andWhere($builder->expr()->in($categoryAlias . '.strId', $categoryStrIds));
	}


	/**
	 * @param ItemSearchQuery $query
	 * @return \Elastica\Filter\BoolAnd
	 */
	protected function createFulltextFilters(ItemSearchQuery $query)
	{
		$filters = new \Elastica\Filter\BoolAnd();

		$range = array();
		$range['gte'] = $query->getPriceFrom();
		$range['lte'] = $query->getPriceTo();
		if ($range = array_filter($range))
		{
			$filters->addFilter(new \Elastica\Filter\Range('price', $range));
		}
		return $filters;
	}

	/**
	 * @param ItemSearchQuery $query
	 * @return \Elastica\Query
	 */
	protected function createFulltextQuery(ItemSearchQuery $query)
	{
		$q = \Elastica\Query::create($query->getQuery());
		$q->addSort(array(
			'id' => array(
				'order' => 'asc',
			)
		));
		return $q;
	}

	public function getItemsFulltext(ItemSearchQuery $query, $maxResults = self::DEFAULT_MAX_RESULTS, $startId = NULL)
	{
		/** @var ElasticaBundle\Repository $repository */
		$repository = $this->rm->getRepository('Muzar\BazaarBundle\Entity\Item');

		$q = $this->createFulltextQuery($query);
		$q->setSize($maxResults);

		$filters = $this->createFulltextFilters($query);

		if ($startId)
		{
			$filters->addFilter(new \Elastica\Filter\Range('id', array(
				'lte' => $startId
			)));
		}

		if ($filters->getFilters())
		{
			$q->setFilter($filters);
		}

		return $repository->find($q);

	}

	public function getItemsFulltextTotal(ItemSearchQuery $query)
	{
		/** @var ElasticaBundle\Repository $repository */
		$repository = $this->rm->getRepository('Muzar\BazaarBundle\Entity\Item');

		$q = \Elastica\Query::create($query->getQuery());
		if ($filters = $this->createFulltextFilters($query))
		{
			$q->setFilter($filters);
		}
		return $repository->createPaginatorAdapter($q)->getTotalHits();
	}
} 