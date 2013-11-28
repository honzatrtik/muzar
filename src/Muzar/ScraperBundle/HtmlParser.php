<?php
/**
 * Date: 25/11/13
 * Time: 15:41
 */

namespace Muzar\ScraperBundle;

use Symfony\Component\DomCrawler\Crawler;

class HtmlParser
{

	public function parse(Crawler $crawler)
	{

		$params = array();

		$node = $crawler->filter('input[name="nabpop"]:checked');
		if ($node->count())
		{
			$params['type'] = trim($node->first()->attr('value'));
		}

		$node = $crawler->filter('select[name="kategorie"] option:selected');
		if ($node->count())
		{
			$params['category'] = trim($node->first()->text());
		}

		$node = $crawler->filter('select[name="kraj"] option:selected');
		if ($node->count())
		{
			$params['region'] = trim($node->first()->text());
		}

		$node = $crawler->filter('input[name="cena"]');
		if ($node->count())
		{
			$params['price'] = trim($node->first()->attr('value'));
		}

		$node = $crawler->filter('select[name="mena"] option:selected');
		if ($node->count())
		{
			$params['currency'] = trim($node->first()->attr('value'));
		}

		// Pocet obrazku
		$node = $crawler->filter('.FormularHodnotaM .InzeratObr');
		$params['images'] = trim($node->count());

		return $params;

	}

} 