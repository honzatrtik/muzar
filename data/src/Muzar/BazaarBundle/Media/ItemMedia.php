<?php
/**
 * Date: 23/04/15
 * Time: 13:20
 */

namespace Muzar\BazaarBundle\Media;


use League\Flysystem\Filesystem;

class ItemMedia implements ItemMediaInterface, \IteratorAggregate
{


	/** @var  Filesystem */
	protected $fs;

	protected $basePath;
	protected $baseUrl;

	function __construct(Filesystem $fs, $basePath = NULL, $baseUrl = '/')
	{
		$this->fs = $fs;
		$this->basePath = $basePath;
		$this->baseUrl = $baseUrl;
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

	public function getNames()
	{
		return array_map(function($file) {
			return $file['path'];
		}, array_filter($this->fs->listContents($this->basePath, TRUE), function($file) {
			return $file['type'] === 'file';
		}));
	}

	public function getUrls()
	{
		return array_map(function($file) {
			return $this->baseUrl . $file['path'];
		}, array_filter($this->fs->listContents($this->basePath, TRUE), function($file) {
			return $file['type'] === 'file';
		}));
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