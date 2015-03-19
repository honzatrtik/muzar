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

class LoadContactData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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

		$path = __DIR__ . '/../../Resources/fixtures/contact.yml';
		$data = Yaml::parse(file_get_contents($path));

		$em = $this->getManager();
		$utils = $this->getEntityUtils();
		
		foreach($data as $contactData)
		{
			$contact = new Contact();
			$utils->fromArray($contact, $contactData);
			$em->persist($contact);
			$em->flush();
			$this->addReference('contact.' . $contact->getId(), $contact);
		}


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