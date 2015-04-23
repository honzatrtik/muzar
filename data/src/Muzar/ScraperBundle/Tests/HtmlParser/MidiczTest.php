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
			'name' => 'Kytarové kombo CRATE 65W',
			'region' => 'Západní Čechy',
			'price' => '3990',
			'currency' => 'CZK',
			'email' => 'kuska.michal@seznam.cz',
			'text' => 'Prodám kytarové tranzistorové kombo s výkonem 65W, 12" reproduktor, 3 kanály s vlastními korekcemi, DSP a hall jsou společné, chromatická ladička, efektová smyčka, cinchový vstup a výstup pro připojení externího reproboxu, trojitý nožní přepínač.Kombo je téměř nepoužívané, perfektní stav. Dostatečné na ozvučení zkušebny i malých klubových prostor. Původní cena 9 000,-.',
			'phone' => '608127700',
			'images' => 1,
			'imageUrls' => array('http://midi.cz/uploads/150763.jpg'),
		);


		ksort($params);
		ksort($expectedParams);


		$this->assertEquals($expectedParams, $params);


	}

}
 