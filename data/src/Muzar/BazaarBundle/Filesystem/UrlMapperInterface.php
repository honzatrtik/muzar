<?php
/**
 * Date: 20/05/15
 * Time: 14:43
 */

namespace Muzar\BazaarBundle\Filesystem;


interface UrlMapperInterface
{
	function map($pathname);
}