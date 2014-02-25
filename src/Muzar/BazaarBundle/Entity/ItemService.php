<?php
/**
 * Date: 24/02/14
 * Time: 13:48
 */

namespace Muzar\BazaarBundle\Entity;

use DoctrineExtensions\NestedSet;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemService 
{
	const DEFAULT_MAX_RESULTS = 60;
	const DEFAULT_CATEGORY_STR_ID = 'root';

	/** @var EntityManager */
	protected $em;

	/** @var  NestedSet\Manager */
	protected $nsm;

	function __construct(EntityManager $em, NestedSet\Manager $nsm)
	{
		$this->em = $em;
		$this->nsm = $nsm;
	}


	public function getItems($categoryStrId = self::DEFAULT_CATEGORY_STR_ID, $maxResults = self::DEFAULT_MAX_RESULTS, $startId = NULL)
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


	public function getItemsTotal($categoryStrId = self::DEFAULT_CATEGORY_STR_ID)
	{
		return $this->getItemsQueryBuilder($categoryStrId)->select('COUNT(i.id)')->getQuery()->getSingleScalarResult();
	}


	protected function getItemsQueryBuilder($categoryStrId, $alias = 'i', $categoryAlias = 'c')
	{
		/** @var ItemRepository $repository */
		$repository = $this->em->getRepository('Muzar\BazaarBundle\Entity\Item');

		// Nacteme si kategorii
		$category = $this->em->getRepository('Muzar\BazaarBundle\Entity\Category')->findOneBy(array(
			'strId' => $categoryStrId
		));

		if (!$category)
		{
			throw new NotFoundHttpException(sprintf('Category "%s" not found.', $categoryStrId));
		}

		$categoryNodeWrapper = $this->nsm->wrapNode($category);
		$descendants = $categoryNodeWrapper->getDescendants();

		$categoryStrIds = array_map(function(NestedSet\NodeWrapper $nodeWrapper) {
			return $nodeWrapper->getNode()->getStrId();
		}, $descendants);

		// Pridame sebe
		$categoryStrIds[] = $category->getStrId();


		$builder = $repository->createQueryBuilder($alias);
		return $builder
			->join($alias . '.categories', $categoryAlias)
			->where($builder->expr()->in($categoryAlias . '.strId', $categoryStrIds));
	}
} 