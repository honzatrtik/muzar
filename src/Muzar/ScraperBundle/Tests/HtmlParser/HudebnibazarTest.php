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
			'type' => 'ruzne',
			'name' => 'ALIEN ROCK - Kytara, klávesy',
			'category' => '• Hudebníci a skupiny',
			'region' => 'Olomoucký kraj',
			'price' => 'dohodou',
			'currency' => 'CZK',
			'images' => '1',
			'email' => 'alien@alienrock.cz',
			'text' => 'Zavedená kapela Alien rock přijme mezi sebe zpívajícího klávesáka a sólového kytaristu (ideálně zpívající). Kdo by měl zájem, nechť nás kontaktuje na alien@alienrock.cz',
			'phone' => '775622371',
		);

		sort($params);
		sort($expectedParams);


		$this->assertEquals($expectedParams, $params);


	}

}
 