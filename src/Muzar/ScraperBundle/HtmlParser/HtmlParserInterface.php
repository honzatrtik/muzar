<?php
/**
 * Date: 25/11/13
 * Time: 15:41
 */

namespace Muzar\ScraperBundle\HtmlParser;

use Symfony\Component\DomCrawler\Crawler;

interface HtmlParserInterface
{

	/**
	 * @param Crawler $crawler
	 * @return array	Array of parsed params
	 */
	public function parse(Crawler $crawler);

} 