<?php
/**
 * Date: 25/11/13
 * Time: 15:41
 */

namespace Muzar\ScraperBundle\HtmlParser;

use Symfony\Component\DomCrawler\Crawler;

class Midicz implements HtmlParserInterface
{

	public function parse(Crawler $crawler)
	{

		$params = array();

		$node = $crawler->filter('.text .druh .nabizim');
		if ($node->count())
		{
			$params['type'] = trim($node->first()->text());
		}

		$node = $crawler->filter('.text h2 a');
		if ($node->count())
		{
			$params['name'] = trim($node->first()->text());
		}

		$node = $crawler->filter('.item .image img');
		if ($node->count())
		{
			$params['images'] = 1;;

			$dom = $node->getNode(0);
			$params['imageUrls'] = array('http://midi.cz' . $node->first()->attr('src')); // Natvrdo ziskavame url
		}
		else
		{
			$params['images'] = 0;
			$params['imageUrls'] = array();
		}

		$node = $crawler->filterXPath('//*[@class="table_info"]//tr[3]/td[2]');
		if ($node->count())
		{
			$params['region'] = trim($node->first()->text());
		}

		$node = $crawler->filter('.priceBox');
		if ($node->count())
		{
			$exploded = explode(' ', trim($node->first()->text()));
			$params['price'] = $exploded[0];
			if (isset($exploded[1]))
			{
				$params['currency'] = $exploded[1];
			}
		}


		// email
		$node = $crawler->filterXPath('//*[@class="table_info"]//tr[4]/td[2]');
		if ($node->count())
		{
			$params['email'] = trim($node->first()->text());
		}

		// telefon
		$node = $crawler->filterXPath('//*[@class="table_info"]//tr[5]/td[2]');
		if ($node->count())
		{
			$params['phone'] = trim($node->first()->text());
		}

		$node = $crawler->filterXPath('//*[@id="mainCol"]/div[1]/div[2]/p[3]');
		if ($node->count())
		{
			$params['text'] = trim($node->first()->text());
		}

		return $params;

	}

} 