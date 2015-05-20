<?php
/**
 * Date: 20/05/15
 * Time: 14:43
 */

namespace Muzar\BazaarBundle\Filesystem;


class BaseUrlMapper implements UrlMapperInterface
{
	private $baseUrl;

	/**
	 * BaseUrlMapper constructor.
	 *
	 * @param $baseUrl
	 */
	public function __construct($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}


	function map($pathname)
	{
		return $this->baseUrl . $pathname;
	}
}