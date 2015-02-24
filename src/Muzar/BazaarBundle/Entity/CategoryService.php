<?php
/**
 * Date: 24/02/14
 * Time: 13:48
 */

namespace Muzar\BazaarBundle\Entity;

use DoctrineExtensions\NestedSet;
use Doctrine\ORM\EntityManager;
use FOS\ElasticaBundle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
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


	/**
	 * @param $categoryStrId
	 * @param bool $includeSelf
	 * @return array
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function getDescendants($categoryStrId, $includeSelf = FALSE)
	{
		// Nacteme si kategorii
		$category = $this->em->getRepository('Muzar\BazaarBundle\Entity\Category')->findOneBy(array(
			'strId' => $categoryStrId
		));

		if (!$category)
		{
			throw new NotFoundHttpException(sprintf('Category "%s" not found.', $categoryStrId));
		}

		$categoryNodeWrapper = $this->nsm->wrapNode($category);
		$descendantNodeWrappers = $categoryNodeWrapper->getDescendants();

		$descendants = array_map(function(NestedSet\NodeWrapper $nodeWrapper) {
			return $nodeWrapper->getNode();
		}, $descendantNodeWrappers);


		if ($includeSelf)
		{
			$descendants[] = $category;
		}

		return $descendants;
	}


	/**
	 * @return Category[]
	 * @throws \RuntimeException
	 */
	public function getSelectableCategories()
	{
		// Nacteme si kategorii
		$root = $this->em->getRepository('Muzar\BazaarBundle\Entity\Category')->findOneBy(array(
			'strId' => self::DEFAULT_CATEGORY_STR_ID
		));

		if (!$root)
		{
			throw new \RuntimeException('Root category not found.');
		}

		$filteredNodeWrappers = array_filter($this->nsm->wrapNode($root)->getDescendants(), function(NestedSet\NodeWrapper $nodeWrapper) use ($root) {
			return $nodeWrapper->getLevel() > 1;
		});

		return array_map(function(NestedSet\NodeWrapper $nodeWrapper) {
			return $nodeWrapper->getNode();
		}, $filteredNodeWrappers);
	}


	public function getTree()
	{
		return $data = $this->createBranch($this->nsm->fetchTree());
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
				'id' => $category->getId(),
				'str_id' => $category->getStrId(),
				'name' => $category->getName(),
				'children' => $this->createBranch($childWrapper),
			);
		}
		return $data;
	}

} 