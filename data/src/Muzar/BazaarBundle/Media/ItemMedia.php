<?php
/**
 * Date: 23/04/15
 * Time: 13:20
 */

namespace Muzar\BazaarBundle\Media;


use League\Flysystem\Filesystem;
use Muzar\BazaarBundle\Filesystem\UrlMapperInterface;

class ItemMedia implements ItemMediaInterface, \IteratorAggregate
{


	/** @var  Filesystem */
	protected $fs;

	protected $basePath;

	/** @var UrlMapperInterface  */
	protected $urlMapper;

	function __construct(Filesystem $fs, UrlMapperInterface $urlMapper, $basePath = NULL)
	{
		$this->fs = $fs;
		$this->urlMapper = $urlMapper;
		$this->basePath = $basePath;
	}

	public function add($name, $path)
	{
		$stream = $this->getStream($path);
		$this->fs->writeStream($this->createPath($name), $stream);
		fclose($stream);
	}

	public function delete($name)
	{
		$this->fs->delete($this->createPath($name));
	}

	public function has($name)
	{
		return $this->fs->has($this->createPath($name));
	}

	public function getNames()
	{
		$basePath = $this->basePath;
		return array_map(function($file) use ($basePath) {

			$path = $file['path'];
			if (strpos($path, $basePath) === 0)
			{
				$path = substr($path, strlen($basePath));
			}
			return trim($path, '/');

		}, array_filter($this->fs->listContents($this->basePath, TRUE), function($file) {
			return $file['type'] === 'file';
		}));
	}

	public function getUrl($name)
	{
		if ($this->has($name))
		{
			return $this->urlMapper->map($this->createPath($name));
		}
		else
		{
			throw new \OutOfBoundsException(sprintf('Media does not exist: "%s".', $name));
		}

	}


	public function getIterator()
	{
		return new \ArrayIterator($this->getNames());
	}

	protected function createPath($name)
	{
		return join('/', array_filter(array(
			$this->basePath,
			$name
		)));
	}

	protected function getStream($path)
	{
		if ($stream = @fopen($path, 'r'))
		{
			return $stream;
		}
		throw new \InvalidArgumentException(sprintf('"%s" is not readable file.', $path));
	}

}