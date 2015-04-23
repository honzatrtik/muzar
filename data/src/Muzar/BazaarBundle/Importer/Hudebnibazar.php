<?php
/**
 * Date: 23/04/15
 * Time: 22:08
 */

namespace Muzar\BazaarBundle\Importer;


use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManager;
use Muzar\BazaarBundle\Entity\Contact;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\User;
use Muzar\ScraperBundle\Entity\Ad;

class Hudebnibazar implements ImporterInterface
{
	/** @var  EntityManager */
	protected $em;

	/** @var  UserManager  */
	protected $userManager;

	function __construct(EntityManager $em, UserManager $userManager)
	{
		$this->em = $em;
		$this->userManager = $userManager;
	}

	/**
	 * @return Item
	 */
	function createItem(Ad $ad)
	{
		if ($ad->getPropertyValueByName('type') !== 'nabidka')
		{
			return NULL;
		}

		if (!$category = $this->getCategory($ad->getPropertyValueByName('categoryId')))
		{
			return NULL;
		}


		$item = new Item();

		$email = $ad->getPropertyValueByName('email');
		list($name, ) = explode('@', $email);

		if (!$user = $this->userManager->findUserByEmail($email))
		{
			$user = new User();
			$user
				->setUsername($email)
				->setEmail($email)
				->setPlainPassword($name);
		}

		$place = array(
			'lat' => NULL,
			'lng' => NULL,
			'place_id' => NULL,
			'address_components' => array(
				'locality' => NULL,
				'country' => NULL,
				'administrative_area_level_1' => $ad->getPropertyValueByName('region'),
				'administrative_area_level_2' => NULL,
			),
		);

		$contact = new Contact();
		$contact
			->setName($name)
			->setEmail($email)
			->setPhone($ad->getPropertyValueByName('phone'))
			->setPlace($place);

		$price = $ad->getPropertyValueByName('price');

		$item
			->setCategory($category)
			->setUser($user)
			->setContact($contact)
			->setName($ad->getPropertyValueByName('name'))
			->setDescription($ad->getPropertyValueByName('text'));

		if (is_numeric($price))
		{
			$item->setPrice($price);
		}
		else
		{
			$item->setNegotiablePrice(TRUE);
		}

		return $item;
	}

	protected function getCategory($categoryId)
	{
		static $categories;
		static $translate = array(
			110100 => 'elektricke-kytary',
			190500 => 'bici',
			190700 => 'bici',
			190100 => 'bici',
			190600 => 'bici',
			110501 => 'snimace',
			110400 => 'kytarove-aparaty',
			110500 => 'kytarove-efekty',
			310200 => 'zvukova-technika',
			310050 => 'zvukova-technika',
			140700 => 'synth',
			110300 => 'baskytary',
		);

		if ($categories === NULL)
		{
			$categories = $this->em->getRepository('Muzar\BazaarBundle\Entity\Category')->createQueryBuilder('c')
				->select('c')
				->indexBy('c', 'c.strId')
				->getQuery()
				->getResult();
		}

		return isset($translate[$categoryId]) && isset($categories[$translate[$categoryId]])
			? $categories[$translate[$categoryId]]
			: NULL;

	}

}