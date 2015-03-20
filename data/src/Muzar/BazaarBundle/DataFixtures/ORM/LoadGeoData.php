<?php
/**
 * Date: 21/01/14
 * Time: 16:35
 */

namespace Muzar\BazaarBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use Muzar\BazaarBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class LoadGeoData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

	/**
	 * @var ContainerInterface
	 */
	private $container;


	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = NULL)
	{
		$this->container = $container;
	}


	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$content = file_get_contents($path = __DIR__ . '/../../Resources/fixtures/geo-data.sql');
		$stmt = $this->container->get('doctrine.orm.entity_manager')->getConnection()->prepare($content);
		$stmt->execute();
	}


	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	function getOrder()
	{
		return 50;
	}


}