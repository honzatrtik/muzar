<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Contact;
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

	public function testPrePersistHooks()
	{

		/** @var CategoryService $categoryService */
		$categoryService = $this->getKernel()->getContainer()
			->get('muzar_bazaar.model_service.category');

		$categories = $categoryService->getSelectableCategories();
		$category = current($categories);


		$contact = new Contact();
		$contact->setEmail('test@mailinator.com')
			->setName('test');

		$item = new Item();

		$item
			->setContact($contact)
			->setCategory($category)
			->setName('Fender Telecaster');

		/** @var EntityManager $em */
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

//	public function testPostLoadItemMediaHooks()
//	{
//
//		/** @var CategoryService $categoryService */
//		$categoryService = $this->getKernel()->getContainer()
//			->get('muzar_bazaar.model_service.category');
//
//		$categories = $categoryService->getSelectableCategories();
//		$category = current($categories);
//
//
//		$contact = new Contact();
//		$contact->setEmail('test@mailinator.com')
//			->setName('test');
//
//		$item = new Item();
//
//		$item
//			->setContact($contact)
//			->setCategory($category)
//			->setName('Fender Telecaster');
//
//		/** @var EntityManager $em */
//		$em = $this->getKernel()->getContainer()
//			->get('doctrine')
//			->getManager();
//		$em->persist($item);
//		$em->flush();
//
//		/** @var Item $item */
//		$item = $em->find('Muzar\BazaarBundle\Entity\Item', $item->getId());
//		$this->assertNotNull($item->getItemMedia());
//	}

	public function testValidate()
	{
		$item = new Item();
		$item->setContact(new Contact());
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
