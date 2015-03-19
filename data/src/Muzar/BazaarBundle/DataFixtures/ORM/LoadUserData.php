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

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
		$um = $this->getUserManager();


		$path = __DIR__ . '/../../Resources/fixtures/user.yml';
		$data = Yaml::parse(file_get_contents($path));

		foreach($data as $userData)
		{
			/** @var User $user */
			$user = $um->createUser();
			$user->setUsername($userData['username']);
			$user->setEmail($userData['email']);
			$user->setPlainPassword($userData['plainPassword']);
			$user->setEnabled(TRUE);

			$um->updateUser($user);
			$manager->flush();

			$this->addReference('user.' . $user->getId(), $user);

		}

	}

	/**
	 * @return UserManager
	 */
	protected function getUserManager()
	{
		return $this->container->get('fos_user.user_manager');
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	function getOrder()
	{
		return 20;
	}


}