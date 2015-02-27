<?php
/**
 * Date: 25/11/13
 * Time: 15:42
 */

namespace Muzar\ProductCatalogBundle\Tests\Importer;

use Muzar\ProductCatalogBundle\Importer\ProductXmlIterator;

class ProductXmlIteratorTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		parent::setUp();
	}


	public function testIsIterator()
	{
		$it = new ProductXmlIterator(__DIR__ . '/kytary-feed.xml');
		$this->assertInstanceOf('\Iterator', $it);
	}

	public function testCount()
	{
		$it = new ProductXmlIterator(__DIR__ . '/kytary-feed.xml');
		$this->assertGreaterThan(10000, iterator_to_array($it));
	}

	public function testItems()
	{
		$it = new ProductXmlIterator(__DIR__ . '/kytary-feed.xml');
		foreach($it as $data)
		{
			$this->assertInternalType('array', $data);
			$this->assertArrayHasKey('PRODUCT', $data);

		}
	}

}
 