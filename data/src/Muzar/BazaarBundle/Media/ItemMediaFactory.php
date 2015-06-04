<?php
/**
 * Date: 23/04/15
 * Time: 13:17
 */

namespace Muzar\BazaarBundle\Media;


use League\Flysystem\Filesystem;
use Muzar\BazaarBundle\Entity\Item;
use Muzar\BazaarBundle\Filesystem\UrlMapperInterface;

class ItemMediaFactory implements ItemMediaFactoryInterface
{
	/** @var  Filesystem */
	protected $fs;

	/** @var UrlMapperInterface  */
	protected $urlMapper;

	function __construct(Filesystem $fs, UrlMapperInterface $urlMapper)
	{
		$this->fs = $fs;
		$this->urlMapper = $urlMapper;
	}

	/**
	 * @param Item $item
	 * @return ItemMediaInterface
	 */
	public function getItemMedia(Item $item)
	{
		return new ItemMedia($this->fs, $this->urlMapper, $this->getBasePath($item));
	}

	protected function getBasePath(Item $item)
	{
		if (!$id = $item->getId())
		{
			throw new \InvalidArgumentException('Id must be set.');
		}

		$hash = substr(sha1($item->getId() . 's0_m3s4cr3tsal.t'), 0, 6);
		return join('/', str_split($hash, 2));
	}

}