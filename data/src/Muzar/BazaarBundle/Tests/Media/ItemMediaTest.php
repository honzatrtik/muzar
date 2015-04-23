<?php

namespace Muzar\BazaarBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet\Manager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\Contact;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\Media\ItemMedia;
use Muzar\BazaarBundle\Media\ItemMediaInterface;
use Muzar\BazaarBundle\Tests\ApiTestCase;
use Symfony\Component\Validator\Validation;


class ItemMediaTest extends ApiTestCase
{


	/**
	 * @var ItemMediaInterface
	 */
	protected $im;


	protected static function cleanupFilesystem()
	{
		$dirPath = __DIR__ . '/root';
		if (is_dir($dirPath))
		{
			foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path)
			{
				$path->isDir() && !$path->isLink()
					? rmdir($path->getPathname())
					: unlink($path->getPathname());
			}
			rmdir(__DIR__ . '/root');
		}


		@unlink(__DIR__ . '/foo');
		@unlink(__DIR__ . '/bar');
	}

	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();
		self::cleanupFilesystem();
	}


	public function tearDown()
	{
		parent::tearDown();
		self::cleanupFilesystem();
	}

	protected function setUp()
	{
		parent::setUp();

		mkdir(__DIR__ . '/root');

		touch(__DIR__ . '/foo');
		touch(__DIR__ . '/bar');

		$fs = new Filesystem(new Local(__DIR__ . '/root'));
		$this->im = new ItemMedia($fs);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testAddNonExistent()
	{
		$this->im->add('test/foo', __DIR__ . '/nonexistent');
	}

	public function testAdd()
	{
		$this->im->add('test/foo', __DIR__ . '/foo');
		$this->im->add('bar', __DIR__ . '/bar');

		$names = $this->im->getNames();
		$expectedNames = array('test/foo', 'bar');

		sort($names);
		sort($expectedNames);

		$this->assertEquals($expectedNames, $names);
	}

	public function testDelete()
	{
		$this->im->add('test/foo', __DIR__ . '/foo');
		$this->im->add('bar', __DIR__ . '/bar');


		$this->im->delete('bar');

		$names = $this->im->getNames();
		$expectedNames = array('test/foo');

		sort($names);
		sort($expectedNames);

		$this->assertEquals($expectedNames, $names);
	}

	public function testIterator()
	{
		$this->assertInstanceOf('\Traversable', $this->im);
		$this->assertInternalType('array', iterator_to_array($this->im));
	}

	public function testGetUrls()
	{
		$this->im->add('test/foo', __DIR__ . '/foo');
		$this->im->add('bar', __DIR__ . '/bar');

		$urls = $this->im->getUrls();
		$this->assertInternalType('array', $urls);
		var_dump($urls);
	}
}
