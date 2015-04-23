<?php
/**
 * Date: 25/11/13
 * Time: 15:42
 */

namespace Muzar\ScraperBundle\Tests\HtmlParser;

use Muzar\ScraperBundle\HtmlParser\Hudebnibazar;
use Symfony\Component\DomCrawler\Crawler;

class HudebnibazarTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		parent::setUp();
	}


	/**
	 * @return \Muzar\ScraperBundle\HtmlParser\Hudebnibazar
	 */
	protected function getParser()
	{
		return new Hudebnibazar();
	}

	public function testIsInterface()
	{
		$this->assertInstanceOf('\Muzar\ScraperBundle\HtmlParser\HtmlParserInterface', $this->getParser());
	}

	public function testParse()
	{
		$parser = $this->getParser();
		$params = $parser->parse(new Crawler(file_get_contents(__DIR__ . '/hudebnibazar.html')));

		$this->assertInternalType('array', $params);


		$expectedParams = array(
			'type' => 'nabidka',
			'name' => 'Prodám velmi zachovalou dvanáctistrunnou kytaru',
			'categoryId' => '110210',
			'category' => '   Elektroakustiky',
			'regionId' => '12',
			'region' => 'Olomoucký kraj',
			'price' => '2000',
			'currency' => 'CZK',
			'images' => '3',
			'imageUrls' => array(
				0 => 'http://img.hudebnibazar.cz/cache/1280x1280-0/img_inz/a038751f79fe8f6b5abe61afa23e2557.jpg',
				1 => 'http://img.hudebnibazar.cz/cache/1280x1280-0/img_inz/2597bcf14ad3f501ef974e1be0c1d4a9.jpg',
				2 => 'http://img.hudebnibazar.cz/cache/1280x1280-0/img_inz/3aa8659d3fc7523c792f19d5907a6de3.jpg'
			),
			'email' => 'foukaljan@seznam.cz',
			'text' => 'Prodám velmi zachovalou a nepoškozenou dvanáctistrunnou kytaru Cremona se zabudovaným funkčním snímačem včetně popruhu a koženkového pouzdra. Mohu zaslat i na dobírku.',
			'phone' => '+420736464211',
		);

		ksort($params);
		ksort($expectedParams);


		$this->assertEquals($expectedParams, $params);




	}

}
 