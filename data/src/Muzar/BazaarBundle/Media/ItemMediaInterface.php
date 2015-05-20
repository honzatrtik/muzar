<?php
/**
 * Date: 23/04/15
 * Time: 13:20
 */

namespace Muzar\BazaarBundle\Media;


interface ItemMediaInterface
{

	public function add($name, $path);
	public function delete($name);
	public function has($name);
	public function getNames();
	public function getUrl($name);

}