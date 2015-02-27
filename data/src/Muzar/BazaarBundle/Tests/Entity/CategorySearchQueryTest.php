<?php

namespace Muzar\BazaarBundle\Tests\Entity;


use Muzar\BazaarBundle\Entity\CategorySearchQuery;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class CategorySearchQueryTest extends ApiTestCase
{


	protected function setUp()
	{
		parent::setUp();
	}


	public function testIsFilled()
	{
		$q = new CategorySearchQuery();

		$this->assertFalse($q->isFilled());

		$q->setQuery('kytara');
		$this->assertTrue($q->isFilled());
	}

	public function testgetQ()
	{
		$q = new CategorySearchQuery();

		$this->assertFalse($q->isFilled());

		$q->setQuery('kytara');
		$this->assertTrue($q->isFilled());
	}

}
