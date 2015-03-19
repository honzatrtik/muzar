<?php
/**
 * Date: 21/01/14
 * Time: 16:35
 */

namespace Muzar\BazaarBundle\DataFixtures\ORM;


use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Muzar\BazaarBundle\Entity\Contact;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\Utils;
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
			return strpos($name, 'category.leaf') !== FALSE;
		});


		// Ziskame si reference na Users
		$userReferenceNames = array_filter(array_keys($this->referenceRepository->getReferences()), function($name) {
			return strpos($name, 'user') !== FALSE;
		});

		// Ziskame si reference na Users
		$contactReferenceNames = array_filter(array_keys($this->referenceRepository->getReferences()), function($name) {
			return strpos($name, 'contact') !== FALSE;
		});


		$path = __DIR__ . '/../../Resources/fixtures/item.yml';
		$data = Yaml::parse(file_get_contents($path));

		$em = $this->getManager();
		$utils = $this->getEntityUtils();

		foreach($data as $itemData)
		{
			$created = new \DateTime();
			$itemData['created'] = $created->setTimestamp($itemData['created']);

			$item = new Item();
			$utils->fromArray($item, $itemData);


			$item->setCreated($created);

			if (count($categoryReferenceNames) && ($index = array_rand($categoryReferenceNames)) !== NULL)
			{
				$category = $this->getReference($categoryReferenceNames[$index]);
				$item->setCategory($category);
			}

			if (count($userReferenceNames) && ($index = array_rand($userReferenceNames)) !== NULL)
			{
				$user = $this->getReference($userReferenceNames[$index]);
				$item->setUser($user);
			}

			if (count($contactReferenceNames) && ($index = array_rand($contactReferenceNames)) !== NULL)
			{
				$contact = $this->getReference($contactReferenceNames[$index]);
				$item->setContact($contact);
			}

			$item->setSlugOnPrePersist();
			$em->persist($item);
		}

		$em->flush();
	}

	/**
	 * @return Utils
	 */
	protected function getEntityUtils()
	{
		return $this->container->get('muzar_bazaar.entity_utils');
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
		return 30;
	}


}