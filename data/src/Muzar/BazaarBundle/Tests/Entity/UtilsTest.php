<?php
/**
 * Date: 17/03/15
 * Time: 22:19
 */

namespace Muzar\BazaarBundle\Tests\Entity;

use Muzar\BazaarBundle\Entity\Contact;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\Utils;
use Muzar\BazaarBundle\Tests\ApiTestCase;
use Muzar\BazaarBundle\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class UtilsTest extends ApiTestCase
{


	/**
	 * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
	 */
	protected function getPropertyAccessorInterfaceMock()
	{
		$mock = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessorInterface');
		return $mock;
	}

	public function testFlattenArray()
	{

		$utils = new Utils($this->getPropertyAccessorInterfaceMock());

		$nested = array(
			'a' => 'a',
			'b' => array(
				'c' => 'bc',
				'd' => 'bd',
			),
			'c' => array(
				'e', 'f', array('g' => 'h')
			),
		);

		$expected = array(
			'a' => 'a',
			'b.c' => 'bc',
			'b.d' => 'bd',
			'c.0' => 'e',
			'c.1' => 'f',
			'c.2.g' => 'h',
		);

		$this->assertEquals($expected, $utils->flattenArray($nested));
	}

	public function testFromArray()
	{

		$item = new Item();

		$accessor = new PropertyAccessor();
		$utils = new Utils($accessor);

		$utils->fromArray($item, array(
			'name' => 'Test name',
			'description' => 'Test description',
		));

		$this->assertEquals('Test name', $item->getName());
		$this->assertEquals('Test description', $item->getDescription());

	}

	public function testFromArrayArrayProperty()
	{

		$item = new Contact();

		$accessor = new PropertyAccessor();
		$utils = new Utils($accessor);

		$utils->fromArray($item, array(
			'place.lat' => 12.4,
			'place.lng' => 50,
			'place.address_components.country' => 'Czech Republic',
		));

		$this->assertEquals(array(
			'lat' => 12.4,
			'lng' => 50,
			'address_components' => array(
				'country' => 'Czech Republic',
			)
		), $item->getPlace());

	}

	public function testFromArrayArrayProperty2()
	{

		$data = json_decode('{"contact":{"place":{"address_components":{"locality":"Praha","administrative_area_level_2":"Hlavní město Praha","administrative_area_level_1":"Hlavní město Praha","country":"Česká republika"},"formatted_address":"Praha, Hlavní město Praha, Česká republika","place_id":"ChIJsbtk6FCUC0cRRgVK6gdmRd8","lat":50.06618539999999,"lng":14.401881799999956},"phone":""}}', TRUE);

		$item = new Item();
		$item->setContact(new Contact());

		$accessor = new PropertyAccessor();
		$utils = new Utils($accessor);

		$utils->fromArray($item, $data);


	}
}
