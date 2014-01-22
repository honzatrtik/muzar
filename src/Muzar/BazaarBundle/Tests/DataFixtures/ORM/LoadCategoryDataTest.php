<?php
/**
 * Date: 21/01/14
 * Time: 17:05
 */

namespace Muzar\BazaarBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Muzar\BazaarBundle\DataFixtures\ORM\LoadCategoryData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoadCategoryDataTest extends WebTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * {@inheritDoc}
	 */
	public function setUp()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();

		$this->em = static::$kernel->getContainer()
			->get('doctrine')
			->getManager()
		;
	}


	public function testLoad()
	{
		$loader = new LoadCategoryData();

		$loader->setReferenceRepository(new ReferenceRepository($this->em));
		$loader->setContainer(static::$kernel->getContainer());

		$loader->load($this->em);
	}


	/**
	 * {@inheritDoc}
	 */
	protected function tearDown()
	{
		parent::tearDown();
		$this->em->close();
	}
}
