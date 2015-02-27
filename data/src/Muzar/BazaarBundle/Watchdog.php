<?php
/**
 * Date: 14/05/14
 * Time: 15:20
 */

namespace Muzar\BazaarBundle;


use Doctrine\ORM\EntityManager;
use Elastica\Index;
use Elastica\Percolator;
use FOS\ElasticaBundle\Persister\ObjectPersister;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Muzar\BazaarBundle\Entity\User;

class Watchdog
{

	/**
	 * @var Percolator
	 */
	protected $percolator;

	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var ObjectPersister
	 */
	protected $persister;

	function __construct(Index $index, EntityManager $em, ObjectPersister $persister = NULL)
	{
		$this->index = $index;
		$this->em = $em;
		$this->persister = $persister;
		$this->percolator = new Percolator($index);
	}

	private function asserQueryIsPersisted(ItemSearchQuery $itemSearchQuery)
	{
		if (\Doctrine\ORM\UnitOfWork::STATE_MANAGED !== $this->em->getUnitOfWork()->getEntityState($itemSearchQuery))
		{
			throw new \InvalidArgumentException('ItemSearchQuery must be persisted.');
		}
	}

	protected function createId(ItemSearchQuery $itemSearchQuery)
	{
		return $itemSearchQuery->getId();
	}

	public function registerQuery(ItemSearchQuery $itemSearchQuery)
	{
		$this->asserQueryIsPersisted($itemSearchQuery);

		return $this->percolator->registerQuery(
			$this->createId($itemSearchQuery),
			$itemSearchQuery->getElasticaQuery(),
			array(
				'user' => array(
					'id' => $itemSearchQuery->getUser()->getId(),
					'email' => $itemSearchQuery->getUser()->getEmail(),
				)
			)
		);
	}

	public function unregisterQuery(ItemSearchQuery $itemSearchQuery)
	{
		$this->asserQueryIsPersisted($itemSearchQuery);
		return $this->percolator->unregisterQuery($this->createId($itemSearchQuery));
	}


	protected function getMatchedQueryIds(Item $item, User $user = NULL)
	{
		$document = $this->persister->transformToElasticaDocument($item);

		// Fix.
		$document->setData(json_decode($document->getData(), TRUE));
		$matches = $this->percolator->matchDoc($document, NULL, 'item');

		$ids = array_map(function($item) {
			return $item['_id'];
		}, $matches);

		return $ids;
	}

	public function getMatchedQueriesCount(Item $item, User $user = NULL)
	{
		return count($this->getMatchedQueryIds($item, $user));
	}

	public function getMatchedQueries(Item $item, User $user = NULL)
	{
		$ids = $this->getMatchedQueryIds($item, $user);
		return count($ids)
			? $this->em->getRepository('Muzar\BazaarBundle\Entity\ItemSearchQuery')->findBy(array('id' => $ids))
			: array();

	}

} 