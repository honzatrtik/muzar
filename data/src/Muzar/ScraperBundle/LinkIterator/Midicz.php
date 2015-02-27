<?php
/**
 * Date: 25/11/13
 * Time: 15:41
 */

namespace Muzar\ScraperBundle\LinkIterator;


use Goutte\Client;
use Symfony\Component\DomCrawler\Link;

class Midicz implements \Iterator
{

	const DEFAULT_URL = 'http://midi.cz';

	const LINK_FILTER_REGEX = '%inzerat/\d+%';


	/**
	 * @var bool
	 */
	protected $initialized = FALSE;

	/**
	 * @var string
	 */
	protected $current;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var Client
	 */
	protected $client;

	/** @var \ArrayIterator */
	protected $it;


	function __construct(Client $client, $url = self::DEFAULT_URL)
	{
		$this->url = $url;
		$this->client = $client;
	}

	protected function initialize()
	{
		if (!$this->initialized)
		{

			$crawler = $this->client->request('GET', $this->url);
			$urls = array_map(function(Link $link) {
				return $link->getUri();
			}, $crawler->filter('a')->links());

			$regex = self::LINK_FILTER_REGEX;

			$urls = array_filter($urls, function($url) use ($regex) {
				return preg_match($regex, $url);
			});

			$urls = array_unique($urls);

			$urls = array_fill_keys($urls, new \DateTime());

			$this->it = new \ArrayIterator($urls);

			$this->initialized = TRUE;
		}
	}

	public function current()
	{
		$this->initialize();
		return $this->it->current();
	}


	public function next()
	{
		$this->initialize();
		$this->it->next();
	}


	public function key()
	{
		$this->initialize();
		return $this->it->key();
	}


	public function valid()
	{
		$this->initialize();
		return $this->it->valid();
	}


	public function rewind()
	{
		$this->initialize();
		$this->it->rewind();
	}


} 