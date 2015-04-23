<?php

namespace Muzar\BazaarBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MuzarBazaarBundle extends Bundle
{
	public function boot()
	{
		parent::boot();

		/** @var EntityManager $em */
		$em = $this->container->get('doctrine.orm.entity_manager');

		$em->getConfiguration()->getEntityListenerResolver()->register($this->container->get('muzar_bazaar.model.entity_listener.category'));
		$em->getConfiguration()->getEntityListenerResolver()->register($this->container->get('muzar_bazaar.model.entity_listener.item_search_query'));
		$em->getConfiguration()->getEntityListenerResolver()->register($this->container->get('muzar_bazaar.model.entity_listener.item'));
	}


}
