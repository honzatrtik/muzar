<?php
/**
 * Date: 25/11/13
 * Time: 15:41
 */

namespace Muzar\ProductCatalogBundle\Importer;


class ProductXmlIterator implements \Iterator
{
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


	function __construct($url)
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
			$this->read($r, 'SHOPITEM');

			$xml = new \SimpleXMLElement($r->readOuterXml());

			// vse, co neni pole prevedem na string
			$current = array_map(function($item) {
				return is_array($item)
					? $item
					: (string) $item;
			}, (array) $xml);

			$this->current = $current;
			$this->key = (string) $xml->PRODUCT;
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