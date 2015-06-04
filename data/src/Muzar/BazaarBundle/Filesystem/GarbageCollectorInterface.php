<?php
/**
 * Date: 20/05/15
 * Time: 13:46
 */

namespace Muzar\BazaarBundle\Filesystem;


use League\Flysystem\FilesystemInterface;

interface GarbageCollectorInterface
{

	function run(FilesystemInterface $fs, $directory = '');

}