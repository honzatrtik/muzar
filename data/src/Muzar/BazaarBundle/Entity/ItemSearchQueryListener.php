<?php
/**
 * Date: 20/01/14
 * Time: 14:12
 */

namespace Muzar\BazaarBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Muzar\BazaarBundle\Watchdog;

class ItemSearchQueryListener
{
	/**
	 * @var Watchdog
	 */
	protected $watchdog;

	public function __construct(Watchdog $watchdog)
	{
		$this->watchdog = $watchdog;
	}

	/**
	 * @ORM\PostPersist
	 */
	public function postPersist(ItemSearchQuery $itemSearchQuery, LifecycleEventArgs $event)
	{
		$this->watchdog->registerQuery($itemSearchQuery);
	}

	public function postRemove(ItemSearchQuery $itemSearchQuery, LifecycleEventArgs $event)
	{
		$this->watchdog->unregisterQuery($itemSearchQuery);
	}

}