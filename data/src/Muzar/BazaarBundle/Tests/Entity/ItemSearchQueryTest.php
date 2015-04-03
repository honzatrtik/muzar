<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class ItemSearchQueryTest extends ApiTestCase
{


	protected function setUp()
	{
		parent::setUp();
	}


	public function testSetters()
	{
		$q = new ItemSearchQuery();

		$q->setPriceTo(2000);
		$this->assertEquals(2000, $q->getPriceTo());

		$q->setPriceFrom(10000);
		$this->assertEquals(10000, $q->getPriceFrom());

		$q->setQuery('kytara');
		$this->assertEquals('kytara', $q->getQuery());

		$q->setCategoryStrId(4);
		$this->assertEquals(4, $q->getCategoryStrId());

	}

	public function testGetHash()
    {
		$q1 = new ItemSearchQuery();
		$q2 = new ItemSearchQuery();


		$q1
			->setQuery('test')
			->setPriceFrom(100)
			->setPriceTo(1000);

		$q2
			->setQuery('test')
			->setPriceFrom(100)
			->setPriceTo(1000);

		$this->assertNotEmpty($q1->createHash());
		$this->assertEquals($q1->createHash(), $q2->createHash());

		$q2->setCategoryStrId(4);
		$this->assertNotEquals($q1->createHash(), $q2->createHash());

    }

	public function testToArray()
	{
		$q1 = new ItemSearchQuery();

		$q1
			->setQuery('test')
			->setPriceFrom(100)
			->setPriceTo(1000);

		$this->assertEquals(array(
			'query' => 'test',
			'priceFrom' => 100,
			'priceTo' => 1000,
			'categoryStrId' => NULL,
			'district' => NULL,
			'region' => NULL,
		), $q1->toArray());
	}

}
