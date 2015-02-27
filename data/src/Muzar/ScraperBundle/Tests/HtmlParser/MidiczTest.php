<?php
/**
 * Date: 25/11/13
 * Time: 15:42
 */

namespace Muzar\ScraperBundle\Tests\HtmlParser;

use Muzar\ScraperBundle\HtmlParser\Hudebnibazar;
use Muzar\ScraperBundle\HtmlParser\Midicz;
use Symfony\Component\DomCrawler\Crawler;

class MidiczTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		parent::setUp();
	}


	/**
	 * @return \Muzar\ScraperBundle\HtmlParser\Midicz
	 */
	protected function getParser()
	{
		return new Midicz();
	}

	public function testIsInterface()
	{
		$this->assertInstanceOf('\Muzar\ScraperBundle\HtmlParser\HtmlParserInterface', $this->getParser());
	}

	public function testParse()
	{
		$parser = $this->getParser();
		$params = $parser->parse(new Crawler(file_get_contents(__DIR__ . '/midicz.html')));

		$this->assertInternalType('array', $params);


		$expectedParams = array(
			'type' => 'Nabízím',
			'name' => 'Lexikon MX200',
			'region' => 'Jižní Čechy',
			'price' => '3500',
			'currency' => 'CZK',
			'email' => 'selltheafro@gmail.com',
			'text' => 'Prodám tento rackový efekt v perfektním stavu. Starý asi 3 roky, 100% stav!!! Vše je jak má, mám k němu originální krabici.
Mohu poslat poštou nebo osobní předání.
PC 6000 Kč',
			'phone' => '607795406',
		);

		sort($params);
		sort($expectedParams);


		$this->assertEquals($expectedParams, $params);


	}

}
 