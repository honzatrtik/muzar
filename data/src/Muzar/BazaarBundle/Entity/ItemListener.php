<?php
/**
 * Date: 20/01/14
 * Time: 14:12
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Muzar\BazaarBundle\Media\ItemMediaFactoryInterface;


class ItemListener
{

	/** @var  ItemMediaFactoryInterface */
	private $factory;

	public function __construct(ItemMediaFactoryInterface $factory)
	{
		$this->factory = $factory;
	}



	/**
	 * @ORM\PostLoad
	 * @param Item $item
	 * @param LifecycleEventArgs $event
	 */
	public function postLoad(Item $item, LifecycleEventArgs $event)
	{
		if ($item->getItemMedia() === NULL)
		{
			$item->setItemMedia($this->factory->getItemMedia($item));
		}
	}

	/**
	 * @ORM\PostPersist
	 * @param Item $item
	 * @param LifecycleEventArgs $event
	 */
	public function postPersist(Item $item, LifecycleEventArgs $event)
	{
		if ($item->getItemMedia() === NULL)
		{
			$item->setItemMedia($this->factory->getItemMedia($item));
		}
	}

}