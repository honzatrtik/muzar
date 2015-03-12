<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Tests\ApiTestCase;
use Symfony\Component\Validator\Validation;


class ItemTest extends ApiTestCase
{


	protected function setUp()
	{
		parent::setUp();

		$this->runCommand('doctrine:schema:drop', array(
			'--force' => TRUE,
			'--no-debug' => TRUE,
		));
		$this->runCommand('doctrine:schema:create', array(
			'--no-debug' => TRUE,
		));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetInvalidStatus()
    {
		$item = new Item();
		$item->setStatus('badstatus');
    }

	public function testPrePersistHooks()
	{
		$item = new Item();
		$item->setName('Fender Telecaster');

		/** @var EntityManager $em */
		/** @var EntityManager em */
		$em = $this->getKernel()->getContainer()
			->get('doctrine')
			->getManager();
		$em->persist($item);
		$em->flush();


		$this->assertNotEmpty($item->getId());
		$this->assertNotEmpty($item->getSlug());
		$this->assertNotEmpty($item->getCreated());

		$this->assertEquals('fender-telecaster', $item->getSlug());
	}

	public function testValidatePrice()
	{
		$item = new Item();
		$validator = $this->getKernel()->getContainer()->get('validator');

		$errors = $validator->validate($item);
		$this->assertNotEmpty($errors);
	}

	public function testGetCategoryStrIds()
	{
		$item = new Item();

		$this->assertEquals(array(), $item->getCategoryStrIds());

		$cat = new Category();
		$cat->setStrId('test');
		$item->setCategory($cat);

		$this->assertEquals(array('test'), $item->getCategoryStrIds());
	}
}
