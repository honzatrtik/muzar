<?php
/**
 * Date: 23/04/15
 * Time: 13:17
 */

namespace Muzar\BazaarBundle\Media;


use League\Flysystem\Filesystem;
use Muzar\BazaarBundle\Entity\Item;

interface ItemMediaFactoryInterface
{

	/**
	 * @param Item $item
	 * @return ItemMediaInterface
	 */
	public function getItemMedia(Item $item);


}