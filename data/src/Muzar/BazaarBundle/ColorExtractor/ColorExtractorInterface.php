<?php
/**
 * Date: 08/05/15
 * Time: 23:07
 */

namespace Muzar\BazaarBundle\ColorExtractor;


interface ColorExtractorInterface
{
	/**
	 * @param string $imagePath
	 * @return string  Hexa like "#ee3300"
	 */
	function extract($imagePath);
}