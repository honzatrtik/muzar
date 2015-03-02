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


	public function testIsFilled()
	{
		$q = new ItemSearchQuery();

		$this->assertFalse($q->isFilled());

		$q->setPriceTo(2000);
		$this->assertTrue($q->isFilled());


		$q->setPriceTo(NULL);
		$this->assertFalse($q->isFilled());

		$q->setPriceFrom(10000);
		$this->assertTrue($q->isFilled());

		$q->setPriceFrom(NULL);
		$this->assertFalse($q->isFilled());

		$q->setQuery('kytara');
		$this->assertTrue($q->isFilled());
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
		), $q1->toArray());
	}
}
