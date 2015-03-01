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
use Muzar\BazaarBundle\Suggestion\QuerySuggesterInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class LoadQuerySuggestionData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
		$path = __DIR__ . '/../../Resources/fixtures/item.yml';
		$data = Yaml::parse(file_get_contents($path));

		$suggester = $this->getQuerySuggester();

		foreach($data as $itemData)
		{
			$suggester->add($itemData['name']);
		}

	}

	/**
	 * @return QuerySuggesterInterface
	 */
	protected function getQuerySuggester()
	{
		return $this->container->get('muzar_bazaar.query_suggester');
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	function getOrder()
	{
		return 4;
	}


}