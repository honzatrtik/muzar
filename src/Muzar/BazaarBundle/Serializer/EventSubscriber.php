<?php
/**
 * Date: 27/02/14
 * Time: 12:35
 */

namespace Muzar\BazaarBundle\Serializer;


use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Muzar\BazaarBundle\Entity\Item;
use Symfony\Component\Routing\Router;

class EventSubscriber implements EventSubscriberInterface
{

	/** @var Router */
	protected $router;

	/** @var  array */
	protected $mapping;

	function __construct(Router $router)
	{
		$this->router = $router;
	}

	public static function getSubscribedEvents()
	{
		return array(
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'addItemLink',
				'class' => 'Muzar\BazaarBundle\Entity\Item'
			),
		);
	}


	public function addItemLink(ObjectEvent $event)
	{

		/** @var Item $item */
		$item = $event->getObject();
		$link = $this->router->generate('muzar_bazaar_item_get', array('id' => $item->getId()), Router::ABSOLUTE_URL);
		$event->getVisitor()->addData('link', $link);

	}
} 