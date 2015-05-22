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

	public function add($name, $stream)
	{
		$stream = $this->assertStream($stream);
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

	protected function assertStream($stream)
	{
		if (is_resource($stream) && (get_resource_type($stream) == 'file' || get_resource_type($stream) == 'stream'))
		{
			return $stream;
		}
		elseif (is_string($stream))
		{
			if ($stream = @fopen($stream, 'r'))
			{
				return $stream;
			}
		}

		throw new \InvalidArgumentException(sprintf('Parameter is not readable stream or file path.'));
	}

}