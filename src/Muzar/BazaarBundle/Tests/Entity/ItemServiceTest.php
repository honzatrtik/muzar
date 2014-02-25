<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use Muzar\BazaarBundle\Entity\ItemService;
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

		/** @var Manager $nsm */
		$nsm = $this->getKernel()->getContainer()->get('muzar_bazaar.nsm.category');

		$this->service = new ItemService($em, $nsm);

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
}
