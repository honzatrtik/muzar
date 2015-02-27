<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Tests\ApiTestCase;
use Symfony\Component\Validator\Validation;


class ItemTest extends ApiTestCase
{


	protected function setUp()
	{
		parent::setUp();
	}


	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetInvalidStatus()
    {
		$item = new Item();
		$item->setStatus('badstatus');
    }


	public function testValidatePrice()
	{
		$item = new Item();
		$validator = $this->getKernel()->getContainer()->get('validator');

		$errors = $validator->validate($item);
		$this->assertNotEmpty($errors);
	}
}
