<?php
/**
 * Date: 25/11/13
 * Time: 15:41
 */

namespace Muzar\ScraperBundle\LinkIterator;


class Hudebnibazar implements \Iterator
{
	const DEFAULT_URL = 'http://hudebnibazar.cz/rss/rss.php';

	/**
	 * @var \XMLReader;
	 */
	protected $reader;

	/**
	 * @var string
	 */
	protected $url;


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


	function __construct($url = self::DEFAULT_URL)
	{
		$this->url = $url;
	}

	protected function getReader($reload = FALSE)
	{
		if ($this->reader === NULL || $reload)
		{
			$this->initialized = FALSE;
			$this->reader = new \XMLReader();
			if (!$this->reader->open($this->url))
			{
				throw new \RuntimeException(sprintf('Can not open: "%s"', $this->url));
			}
		}
		return $this->reader;
	}

	/**
	 * Cte xml k dalsimu elementu s nazvem $name
	 * @param \XMLReader $r
	 * @param string|array $name
	 * @throws \RuntimeException
	 */
	protected function read(\XMLReader $r, $name)
	{
		$names = is_array($name)
			? $name
			: array($name);

		do
		{
			$read = $r->read();
			$nt = $r->nodeType;
			$nn = $r->name;

		}
		while($read && !(in_array($nn, $names) && ($nt === \XMLReader::ELEMENT)));

		if (!$read)
		{
			throw new \RuntimeException(sprintf('Can not read any of elements "%s".', implode(',', $names)));
		}
	}

	protected function initialize()
	{
		if (!$this->initialized)
		{
			$this->initialized = TRUE;
			$this->next();
		}
	}

	public function current()
	{
		$this->initialize();
		return $this->current;
	}


	public function next()
	{
		$this->initialize();

		$this->current = NULL;
		$this->key = NULL;

		$r = $this->getReader();
		try
		{
			$this->read($r, 'item');

			$xml = new \SimpleXMLElement($r->readOuterXml());
			if ($xml->link && $xml->pubDate)
			{
				$this->current = new \DateTime((string) $xml->pubDate);

				// Zjitime si edit link
				parse_str(parse_url($xml->link, PHP_URL_QUERY), $vars);
				$this->key = (string) 'http://hudebnibazar.cz/formular.php?ID=' . $vars['ID'];
			}
		}
		catch (\RuntimeException $e)
		{
			// Nic nedelame
		}



	}


	public function key()
	{
		$this->initialize();
		return $this->key;
	}


	public function valid()
	{
		$this->initialize();
		return (bool) $this->current;
	}


	public function rewind()
	{
		// Reload dokumentu
		$this->getReader(TRUE);
	}

	public function __destruct()
	{
		if ($this->reader)
		{
			$this->reader->close();
		}
	}

} 