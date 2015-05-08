<?php
/**
 * Date: 08/05/15
 * Time: 23:12
 */

namespace Muzar\BazaarBundle\ColorExtractor;


use League\ColorExtractor\Client;

class League implements ColorExtractorInterface
{
	/** @var  Client */
	protected $leagueColorExtractor;

	/**
	 * League constructor.
	 *
	 * @param Client $leagueColorExtractor
	 */
	public function __construct(Client $leagueColorExtractor)
	{
		$this->leagueColorExtractor = $leagueColorExtractor;
	}

	/**
	 * @param string $imagePath (jpeg)
	 * @return string  Hexa like "#ee3300"
	 */
	function extract($imagePath)
	{
		$palette = $this->leagueColorExtractor->loadJpeg($imagePath)->extract();
		return $palette[0];

	}


}