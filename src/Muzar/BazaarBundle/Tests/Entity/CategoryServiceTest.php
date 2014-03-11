<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use FOS\ElasticaBundle\Manager\RepositoryManager;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class CategoryServiceTest extends ApiTestCase
{

	/** @var  CategoryService */
	protected $service;

	protected function setUp()
	{
		parent::setUp();

		/** @var EntityManager em */
		$em = $this->getKernel()->getContainer()
			->get('doctrine')
			->getManager();


		/** @var Manager $nsm */
		$nsm = $this->getKernel()->getContainer()->get('muzar_bazaar.nsm.category');

		$this->service = new CategoryService($em, $nsm);
		$this->runCommandDropCreateFixtures();

	}

	public function testGetTree()
	{
		$self = $this;
		$tree = $this->service->getTree();

		$testBranch = function(array $branch) use ($self, & $testBranch) {

			$this->assertArrayHasKey('strId', $branch);
			$this->assertArrayHasKey('name', $branch);
			$this->assertArrayHasKey('children', $branch);

			foreach($branch['children'] as $childBranch)
			{
				$testBranch($childBranch);
			}

		};

		foreach($tree as $branch)
		{
			$testBranch($branch);
		}

	}

	public function testGetSelectableCategories()
	{
		/** @var Manager $nsm */
		$nsm = $this->getKernel()->getContainer()->get('muzar_bazaar.nsm.category');


		$categories = $this->service->getSelectableCategories();
		foreach($categories as $category)
		{
			$node = $nsm->wrapNode($category);
			$ancestors = $node->getAncestors();
			$this->assertGreaterThan(1, count($ancestors));
			$this->assertGreaterThan(1, $node->getLevel());
		}

	}

}
