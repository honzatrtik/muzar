<?php
/**
 * Date: 23/04/15
 * Time: 22:02
 */

namespace Muzar\BazaarBundle\Importer;

use Muzar\BazaarBundle\Entity\Item;
use Muzar\ScraperBundle\Entity\Ad;

interface ImporterInterface
{
	/**
	 * @return Item
	 */
	function createItem(Ad $ad);
}