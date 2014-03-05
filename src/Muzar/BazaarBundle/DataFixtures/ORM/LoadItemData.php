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
use Doctrine\ORM\EntityManager;
use Muzar\BazaarBundle\Entity\Item;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Yaml\Yaml;

class LoadItemData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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

		// Ziskame si reference na Category, ktere jsou listy
		$categoryReferenceNames = array_filter(array_keys($this->referenceRepository->getReferences()), function($name) {
			return strpos($name, 'leaf') !== FALSE;
		});



		$path = __DIR__ . '/../../Resources/fixtures/item.yml';
		$data = Yaml::parse(file_get_contents($path));

		$em = $this->getManager();

		foreach($data as $itemData)
		{
			$item = new Item();
			$item->setName($itemData['name']);
			$item->setPrice($itemData['price']);

			$created = new \DateTime();
			$created->setTimestamp($itemData['created']);

			$item->setCreated($created);

			if (count($categoryReferenceNames) && ($index = array_rand($categoryReferenceNames)) !== NULL)
			{
				$category = $this->getReference($categoryReferenceNames[$index]);
				$item->setCategory($category);
			}


			$em->persist($item);
		}

		$em->flush();
	}

	/**
	 * @return EntityManager
	 */
	protected function getManager()
	{
		return $this->container->get('doctrine.orm.entity_manager');
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	function getOrder()
	{
		return 1;
	}


}