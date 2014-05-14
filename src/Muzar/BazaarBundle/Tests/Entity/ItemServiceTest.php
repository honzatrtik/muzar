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
		$items = $this->service->getItems(ItemService::DEFAULT_CATEGORY_STR_ID, 1);
		$this->assertCount(1, $items);
	}

	public function testStartId()
	{
		$items = $this->service->getItems(ItemService::DEFAULT_CATEGORY_STR_ID, 10);
		$startId = $items[9]->getId();
		$items = $this->service->getItems(ItemService::DEFAULT_CATEGORY_STR_ID, 10, $startId);

		$this->assertEquals($startId, $items[0]->getId());
	}

	public function testResultAndTotalEqual()
    {
		$items = $this->service->getItems(ItemService::DEFAULT_CATEGORY_STR_ID, PHP_INT_MAX);
		$this->assertEquals($this->service->getItemsTotal(ItemService::DEFAULT_CATEGORY_STR_ID), count($items));
    }

	public function testFulltextQuery()
	{
		$holder = $this->getFulltextItemParamHolder('kytara');
		$items = $this->service->getItemsFulltext($holder, 1024); // Jinak  preteceme
		foreach($items as $item)
		{
			$this->assertInstanceOf('Muzar\BazaarBundle\Entity\Item', $item);
		}
	}

	protected function getFulltextItemParamHolder($query = '', $priceFrom = NULL, $priceTo = NULL)
	{
		$holder = new ItemSearchQuery();
		return $holder->setQuery($query)
			->setPriceFrom($priceFrom)
			->setPriceTo($priceTo);
	}

	public function testFulltextQueryResultAndTotalEqual()
	{
		$holder = $this->getFulltextItemParamHolder('kytara');
		$items = $this->service->getItemsFulltext($holder, 1024); // Jinak  preteceme
		$this->assertEquals($this->service->getItemsFulltextTotal($holder), count($items));
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
