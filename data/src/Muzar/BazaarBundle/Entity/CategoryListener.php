<?php
/**
 * Date: 20/01/14
 * Time: 14:12
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use DoctrineExtensions\NestedSet;
use Doctrine\ORM\Mapping as ORM;
use Muzar\BazaarBundle\Entity\Category;

class CategoryListener
{
	/** @var \DoctrineExtensions\NestedSet\Manager  */
	protected $nsm;

	public function __construct(NestedSet\Manager $nsm)
	{
		$this->nsm = $nsm;
	}

	/**
	 * Uklada cestu ke kategorii automaticky do db
	 *
	 * @ORM\PostPersist
	 */
	public function postPersist(Category $category, LifecycleEventArgs $event)
	{
		$em = $event->getEntityManager();

		$stringPath = array_map(function(Category $category) {
			return $category->getName();
		}, $this->getPath($this->nsm->wrapNode($category)));

		$wrappedNode = $this->nsm->wrapNode($category);

		$category->setPath(implode(' > ', $stringPath));
		$category->setAncestors($this->getPath($wrappedNode, TRUE, FALSE));
		$em->persist($category);
		$em->flush($category);
	}


	/**
	 * Naplni property ancestors
	 * @ORM\PostLoad
	 * @param Category $category
	 * @param LifecycleEventArgs $event
	 */
	public function postLoad(Category $category, LifecycleEventArgs $event)
	{
		$em = $event->getEntityManager();
		$wrappedNode = $this->nsm->wrapNode($category);
		$category->setAncestors($this->getPath($wrappedNode, TRUE, FALSE));
	}

	protected function getPath(NestedSet\NodeWrapper $wrappedNode, $excludeRoot = TRUE, $includeSelf = TRUE)
	{
		$path = array();

		$ancestors = $wrappedNode->getAncestors();
		if ($excludeRoot)
		{
			array_shift($ancestors);
		}
		foreach($ancestors as $ancestor)
		{
			$path[] = $ancestor;
		}
		if ($includeSelf)
		{
			$path[] = $wrappedNode;
		}

		return array_map(function(NestedSet\NodeWrapper $nodeWrapper) {
			return $nodeWrapper->getNode();
		}, $path);

	}
}