<?php
/**
 * Date: 25/11/13
 * Time: 15:42
 */

namespace Muzar\ScraperBundle\Tests\LinkIterator;

use Guzzle\Http\Client;
use Muzar\ScraperBundle\LinkIterator\Midicz;
use Symfony\Component\DomCrawler\Crawler;

class MidiczTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		parent::setUp();
	}

	protected function getIterator()
	{
		$clientMock = $this->getMock('\Goutte\Client', array(
			'request',
		));

		$crawler = new Crawler(file_get_contents(__DIR__ . '/midi.html'), 'http://midi.cz');

		$clientMock->expects($this->any())
			->method('request')
			->will($this->returnValue($crawler));

		return new Midicz($clientMock, __DIR__ . '/midi.html');
	}

	public function testIsIterator()
	{
		$it = $this->getIterator();
		$this->assertInstanceOf('\Iterator', $it);
	}

	public function testCount()
	{
		$it = $this->getIterator();
		$this->assertCount(49, iterator_to_array($it));
	}

	public function testItems()
	{
		$it = $this->getIterator();
		foreach($it as $link => $pubDate)
		{
			$this->assertInstanceOf('\DateTime', $pubDate);
			$this->assertNotEmpty(filter_var($link, FILTER_VALIDATE_URL));
		}
	}

}
 