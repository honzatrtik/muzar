<?php
/**
 * Date: 24/02/14
 * Time: 13:48
 */

namespace Muzar\BazaarBundle\Entity;

use DoctrineExtensions\NestedSet;
use Doctrine\ORM\EntityManager;
use Elastica\Query;
use FOS\ElasticaBundle;
use Muzar\BazaarBundle\Elastica\QueryFactory;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemService 
{
	const DEFAULT_MAX_RESULTS = 30;
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

	/**
	 * @param $id
	 * @return Item
	 */
	public function getItem($id)
	{
		if (!$item = $this->em->getRepository('Muzar\BazaarBundle\Entity\Item')->find($id))
		{
			throw new NotFoundHttpException(sprintf('Item not found: %d.', $id));
		}
		return $item;
	}

	/**
	 * @param ItemSearchQuery $query
	 * @return Query
	 */
	protected function createFulltextQuery(ItemSearchQuery $query)
	{
		$q = $query->createElasticaQuery();
		$q->addSort(array(
			'id' => array(
				'order' => 'desc',
			)
		));
		return $q;
	}

	public function getItems(ItemSearchQuery $query = NULL, $maxResults = self::DEFAULT_MAX_RESULTS, $startId = NULL)
	{
		$query = $query ?: new ItemSearchQuery();

		/** @var ElasticaBundle\Repository $repository */
		$repository = $this->rm->getRepository('Muzar\BazaarBundle\Entity\Item');

		$q = $this->createFulltextQuery($query);
		$q->setSize($maxResults);

		if ($startId)
		{
			$q->setPostFilter(new \Elastica\Filter\Range('id', array(
				'lte' => $startId
			)));
		}

		return $repository->find($q);

	}

	public function getItemsTotal(ItemSearchQuery $query = NULL)
	{
		$query = $query ?: new ItemSearchQuery();

		/** @var ElasticaBundle\Repository $repository */
		$repository = $this->rm->getRepository('Muzar\BazaarBundle\Entity\Item');
		$q = $this->createFulltextQuery($query);
		return $repository->createPaginatorAdapter($q)->getTotalHits();
	}
} 