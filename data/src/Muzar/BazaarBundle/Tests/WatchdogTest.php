<?php

namespace Muzar\BazaarBundle\Tests;

use Doctrine\ORM\EntityManager;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemSearchQuery;
use Muzar\BazaarBundle\Entity\User;
use Muzar\BazaarBundle\Watchdog;

class WatchdogTest extends ApiTestCase
{
	/**
	 * @var Watchdog
	 */
	protected $watchdog;

	protected function setUp()
	{
		parent::setUp();

		$indexName = $this->getKernel()->getContainer()->getParameter('elasticsearch_index_name');
		$persister = $this->get(sprintf('fos_elastica.object_persister.%s.item', $indexName));

		$em = $this->get('doctrine')->getManager();
		$index = $this->get('fos_elastica.index');

		$this->watchdog = new Watchdog($index, $em, $persister);
	}



	public function testRegisterQuery()
	{
		/** @var EntityManager $em */
		$em = $this->get('doctrine')->getManager();
		$um = $this->get('fos_user.user_manager');

		/** @var User $user */
		$user = $um->createUser();
		$user->setPlainPassword('John1');
		$user->setUsername('John1');
		$user->setEmail('john1.doe@example.com');

		$um->updateUser($user);

		$q = new ItemSearchQuery();
		$q->setPriceTo(1)
			->setPriceFrom(0)
			->setUser($user);

		$em->persist($q);
		$em->flush();

		$result = $this->watchdog->registerQuery($q);
		$this->assertTrue($result->isOk());
	}

	public function testGetMatchedQueries()
	{
		/** @var EntityManager $em */
		$em = $this->get('doctrine')->getManager();
		$um = $this->get('fos_user.user_manager');

		/** @var User $user */
		$user = $um->createUser();
		$user->setPlainPassword('John2');
		$user->setUsername('John2');
		$user->setEmail('john2.doe@example.com');
		$um->updateUser($user);


		$q1 = new ItemSearchQuery();
		$q1
			->setPriceFrom(900)
			->setPriceTo(1000)
			->setUser($user);

		$em->persist($q1);

		$q2 = new ItemSearchQuery();
		$q2
			->setQuery('basa')
			->setUser($user);

		$em->persist($q2);

		$em->flush();

		$result = $this->watchdog->registerQuery($q1);
		$result = $this->watchdog->registerQuery($q2);
		$this->assertTrue($result->isOk());

		$item1 = new Item();
		$item1->setUser($user)
			->setPrice(950)
			->setName('Kytara fender');

		$matchedQueries = $this->watchdog->getMatchedQueries($item1);

		$this->assertInternalType('array', $matchedQueries);

		$this->assertCount(1, $matchedQueries);
		foreach($matchedQueries as $matchedQuery)
		{
			$this->assertInstanceOf('Muzar\BazaarBundle\Entity\ItemSearchQuery', $matchedQuery);
		}

		$item2 = new Item();
		$item2->setUser($user)
			->setPrice(950)
			->setName('basa precision');

		$matchedQueries = $this->watchdog->getMatchedQueries($item2);

		$this->assertInternalType('array', $matchedQueries);
		$this->assertCount(2, $matchedQueries);
		foreach($matchedQueries as $matchedQuery)
		{
			$this->assertInstanceOf('Muzar\BazaarBundle\Entity\ItemSearchQuery', $matchedQuery);
		}

	}


}
