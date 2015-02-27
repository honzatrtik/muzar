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
use DoctrineExtensions\NestedSet\Config;
use DoctrineExtensions\NestedSet\Manager;
use DoctrineExtensions\NestedSet\NodeWrapper;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

class LoadCategoryData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
		$nsm = $this->getNsm();

		$path = __DIR__ . '/../../Resources/fixtures/category.yml';
		$data = Yaml::parse(file_get_contents($path));

		$category = new Category();
		$category->setStrId($data['strId']);
		$category->setName($data['name']);

		// Root node pro kategorie
		$nsm->createRoot($category);

		$node = $nsm->wrapNode($category);
		$this->createChildren($node, $data);

	}

	/**
	 * @return Manager
	 */
	protected function getNsm()
	{
		return $this->container->get('muzar_bazaar.nsm.category');
	}


	protected function createChildren(NodeWrapper $parentNode, $parentData)
	{
		if (isset($parentData['children']))
		{
			foreach($parentData['children'] as $childData)
			{
				$child = new Category();
				$child->setStrId($childData['strId']);
				$child->setName($childData['name']);

				$childNode = $parentNode->addChild($child);

				// Ulozime si referenci na list / vetev
				if (empty($childData['children']))
				{
					$this->addReference('category.leaf.' . $child->getId(), $child);
				}
				else
				{
					$this->addReference('category.branch.' . $child->getId(), $child);
				}

				// Rekurzivne vytvorime deti
				$this->createChildren($childNode, $childData);
			}
		}
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	function getOrder()
	{
		return 0;
	}


}