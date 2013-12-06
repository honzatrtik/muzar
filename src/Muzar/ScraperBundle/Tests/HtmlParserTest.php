<?php
/**
 * Date: 25/11/13
 * Time: 15:42
 */

namespace Muzar\ScraperBundle\Tests;

use Muzar\ScraperBundle\HtmlParser;
use Symfony\Component\DomCrawler\Crawler;

class HtmlParserTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		parent::setUp();
	}


	/**
	 * @return HtmlParser
	 */
	protected function getParser()
	{
		return new HtmlParser();
	}

	public function testParse()
	{
		$parser = $this->getParser();
		$params = $parser->parse(new Crawler(file_get_contents(__DIR__ . '/page.html')));

		$this->assertInternalType('array', $params);


		$expectedParams = array(
			'type' => 'ruzne',
			'name' => 'ALIEN ROCK - Kytara, klávesy',
			'category' => '• Hudebníci a skupiny',
			'region' => 'Olomoucký kraj',
			'price' => 'dohodou',
			'currency' => 'CZK',
			'images' => 1
		);

		$this->assertEquals($expectedParams, $params);


	}

}
 