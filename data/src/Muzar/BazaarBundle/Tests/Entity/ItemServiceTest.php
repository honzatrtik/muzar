<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use FOS\ElasticaBundle\Manager\RepositoryManager;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemServiceTest extends ApiTestCase
{

	/** @var  ItemService */
	protected $service;

	protected function setUp()
	{
		parent::setUp();

		/** @var EntityManager em */
		$em = $this->getKernel()->getContainer()
			->get('doctrine')
			->getManager();


		/** @var RepositoryManager $rm */
		$rm = $this->getKernel()->getContainer()->get('fos_elastica.manager');

		/** @var CategoryService $cs */
		$cs = $this->getKernel()->getContainer()->get('muzar_bazaar.model_service.category');

		$this->service = new ItemService($em, $rm, $cs);
		$this->runCommandDropCreateFixtures();

	}

	public function testLimit()
	{
		$items = $this->service->getItems(NULL, 1);
		$this->assertCount(1, $items);
	}

	public function testStartId()
	{
		$items = $this->service->getItems(NULL, 10);
		$startId = $items[9]->getId();
		$items = $this->service->getItems(NULL, 10, $startId);

		$this->assertEquals($startId, $items[0]->getId());
	}

	public function testResultAndTotalEqual()
    {
		$items = $this->service->getItems(NULL, 1024); // Elastica pretece na PHP_MAX_INT
		$this->assertEquals($this->service->getItemsTotal(NULL), count($items));
    }

	public function testQuery()
	{
		$holder = $this->getItemParamHolder('kytara');
		$items = $this->service->getItems($holder, 1024); // Jinak  preteceme
		foreach($items as $item)
		{
			$this->assertInstanceOf('Muzar\BazaarBundle\Entity\Item', $item);
		}
	}

	public function testQueryAndCategoryAndPrice()
	{
		$holder = $this->getItemParamHolder('kytara', 1000, 5000, 'kytary');
		$items = $this->service->getItems($holder, 1024);
		
	}

	protected function getItemParamHolder($query = '', $priceFrom = NULL, $priceTo = NULL, $categoryStrId = NULL)
	{
		$holder = new ItemSearchQuery();
		return $holder->setQuery($query)
			->setPriceFrom($priceFrom)
			->setPriceTo($priceTo)
			->setCategoryStrId($categoryStrId);
	}

	public function testQueryResultAndTotalEqual()
	{
		$holder = $this->getItemParamHolder('kytara');
		$items = $this->service->getItems($holder, 1024); // Jinak  preteceme
		$this->assertEquals(count($items), $this->service->getItemsTotal($holder));
	}

	public function testGetItem()
	{
		$item = $this->service->getItem(1);
		$this->assertInstanceOf('Muzar\BazaarBundle\Entity\Item', $item);
	}

	/**
	 * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function testGetItemNonexisten()
	{
		$this->service->getItem(-666);
	}
}
