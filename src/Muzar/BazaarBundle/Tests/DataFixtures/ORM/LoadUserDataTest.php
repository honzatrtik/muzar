<?php
/**
 * Date: 21/01/14
 * Time: 17:05
 */

namespace Muzar\BazaarBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManager;
use Muzar\BazaarBundle\DataFixtures\ORM\LoadUserData;
use Muzar\BazaarBundle\Tests\ApiTestCase;

class LoadUserDataTest extends ApiTestCase
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
		/** @var EntityManager em */
		$this->em = $this->getKernel()->getContainer()
			->get('doctrine')
			->getManager()
		;

		$this->runCommand('doctrine:schema:drop', array(
			'--force' => TRUE
		));
		$this->runCommand('doctrine:schema:create', array());

	}


	public function testLoad()
	{
		$loader = new LoadUserData();

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

		$this->runCommand('doctrine:schema:drop', array(
			'--force' => TRUE
		));
		$this->runCommand('doctrine:schema:create', array());

		$this->em->close();
	}
}