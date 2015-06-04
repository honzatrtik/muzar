<?php
/**
 * Date: 20/05/15
 * Time: 13:46
 */

namespace Muzar\BazaarBundle\Filesystem;


use League\Flysystem\FilesystemInterface;

class ClearAll implements GarbageCollectorInterface
{
	public function run(FilesystemInterface $fs, $directory = '')
	{
		foreach($fs->listContents($directory) as $file)
		{
			if ($file['type'] === 'dir')
			{
				$fs->deleteDir($file['path']);
			}
			else
			{
				$fs->delete($file['path']);
			}
		}
	}
}