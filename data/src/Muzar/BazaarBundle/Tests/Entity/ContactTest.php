<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\Contact;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Tests\ApiTestCase;
use Symfony\Component\Validator\Validation;


class ContactTest extends ApiTestCase
{


	protected function setUp()
	{
		parent::setUp();
	}


	public function testPlaceGetters()
	{
		$c = new Contact();
		$c->setPlace(json_decode('{"lat":23,"lng":22,"place_id":"sadasd","address_components":{"locality":"Pribram","country":"CR","administrative_area_level_1":"Stredocesky kraj","administrative_area_level_2":"Pribram"}}', TRUE));


		$this->assertEquals('Pribram', $c->getCity());
		$this->assertEquals('CR', $c->getCountry());
		$this->assertEquals('Stredocesky kraj', $c->getRegion());
		$this->assertEquals('Pribram', $c->getDistrict());
	}


}
