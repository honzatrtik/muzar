<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use FOS\ElasticaBundle\Manager\RepositoryManager;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class CategoryListenerTest extends ApiTestCase
{

	/** @var  Manager */
	protected $nsm;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	protected function setUp()
	{
		parent::setUp();

		/** @var Manager $nsm */
		$this->nsm = $this->getKernel()->getContainer()->get('muzar_bazaar.nsm.category');
		$this->nsm->reset();

		/** @var EntityManager em */
		$this->em =  $this->getKernel()->getContainer()
			->get('doctrine')
			->getManager();

		$this->em->flush();


	}

	public function testPathIsSet()
	{

		$category1 = new Category();
		$category1->setStrId('1');
		$category1->setName('1');

		$category2 = new Category();
		$category2->setStrId('2');
		$category2->setName('2');

		// Root node pro kategorie
		$rootNodeWrapper = $this->nsm->fetchTree();
		$category1NodeWrapper = $rootNodeWrapper->addChild($category1);
		$category1NodeWrapper->addChild($category2);

		$this->em->flush();

		$this->assertNotEmpty($category2->getPath());
		$this->assertEquals('1 > 2', $category2->getPath());
	}

	public function testGetAncestors()
	{

		$category1 = new Category();
		$category1->setStrId('3');
		$category1->setName('3');

		$category2 = new Category();
		$category2->setStrId('4');
		$category2->setName('4');

		// Root node pro kategorie
		$rootNodeWrapper = $this->nsm->fetchTree();
		$category1NodeWrapper = $rootNodeWrapper->addChild($category1);
		$category1NodeWrapper->addChild($category2);

		$this->em->flush();

		$ancestors = $category2->getAncestors();

		$this->assertInternalType('array', $ancestors);
		$this->assertCount(1, $ancestors);
		$this->assertTrue(in_array($category1, $ancestors));


	}
}
